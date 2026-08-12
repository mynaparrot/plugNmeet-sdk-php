<?php

/*
 * Copyright (c) 2022 onward MynaParrot
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace Mynaparrot\Plugnmeet;

use DateTime;
use DateTimeZone;

/**
 * Formats analytics data and calculates bounded durations.
 *
 * @package PlugNmeet
 */
class AnalyticsFormatter
{
    /**
     * @var array
     */
    private array $rawData = [];
    /**
     * @var array
     */
    private array $roomData = [];
    /**
     * @var array
     */
    private array $usersData = [];
    /**
     * @var DateTimeZone
     */
    private DateTimeZone $timezone;

    /**
     * @var string[]
     */
    protected array $roomFields = [
        "room_id", "room_title", "room_creation", "room_ended", "room_duration",
        "room_total_users", "enabled_e2ee", "recording_status", "rtmp_status",
        "external_media_player_status", "etherpad_status", "external_display_link_status",
        "ingress_created", "breakout_room",
    ];

    /**
     * @var string[]
     */
    protected array $userFields = [
        "name", "ex_user_id", "is_admin", "duration", "joined", "left", "mic_status",
        "mic_muted", "mic_duration", "talked", "talked_duration", "webcam_status",
        "webcam_duration", "raise_hand", "voted_poll", "whiteboard_annotated",
        "whiteboard_files", "screen_share_status", "public_chat", "private_chat",
        "chat_files", "interface_invisible", "connection_quality",
    ];

    /**
     * @param array  $rawData      Raw analytics data from plugNmeet-server
     * @param string $userTimezone User timezone
     */
    public function __construct(array $rawData, string $userTimezone = 'UTC')
    {
        $this->rawData = $rawData;

        try {
            $this->timezone = new DateTimeZone($userTimezone);
        } catch (\Exception $exception) {
            $this->timezone = new DateTimeZone('UTC');
        }

        $this->formatRoomData();
        $this->formatUsersData();
    }

    /**
     * Get formatted analytics data.
     *
     * @return array
     */
    public function getFormattedEventData(): array
    {
        return [
            "room"  => $this->roomData,
            "users" => $this->usersData,
        ];
    }

    /**
     * Get raw analytics data.
     *
     * @return array
     */
    public function getRawAnalyticsData(): array
    {
        return $this->rawData;
    }

    /**
     * Get user fields.
     *
     * @return string[]
     */
    public function getUserFields(): array
    {
        return $this->userFields;
    }

    /**
     * Get room fields.
     *
     * @return string[]
     */
    public function getRoomFields(): array
    {
        return $this->roomFields;
    }

    /**
     * Format basic room data.
     *
     * @return void
     */
    private function formatRoomData(): void
    {
        foreach ($this->rawData["room"] ?? [] as $key => $data) {
            if ($key === "events") {
                continue;
            }

            if (($key === "room_creation" || $key === "room_ended") && is_numeric($data)) {
                $data = $this->formatTimestamp($data, false);
            }

            $this->roomData[$key] = $data;
        }

        $events = $this->rawData["room"]["events"] ?? [];
        if (is_array($events)) {
            $this->formatRoomEvents($events);
        }
    }

    /**
     * Format room events.
     *
     * @param array $events
     * @return void
     */
    private function formatRoomEvents(array $events): void
    {
        foreach ($events as $event) {
            if (!is_array($event)) {
                continue;
            }

            $name   = $event["name"] ?? null;
            $values = is_array($event["values"] ?? null) ? $event["values"] : [];

            switch ($name) {
                case "recording_status":
                case "rtmp_status":
                case "external_media_player_status":
                case "external_display_link_status":
                case "etherpad_status":
                    $this->roomData[$name] = $this->countStatusStartTypeEvent($values);
                    break;

                case "ingress_created":
                case "breakout_room":
                    $this->roomData[$name] = max(0, (int) ($event["total"] ?? 0));
                    break;

                case "whiteboard_files":
                    $this->roomData[$name] = $values;
                    break;

                case "poll_added":
                    $this->roomData["polls"] = $this->formatRoomPolls($values);
                    break;
            }
        }
    }

    /**
     * Format user data.
     *
     * @return void
     */
    private function formatUsersData(): void
    {
        foreach ($this->rawData["users"] ?? [] as $user) {
            if (!is_array($user)) {
                continue;
            }

            $formattedUser = [];
            foreach ($user as $key => $value) {
                if ($key !== "events") {
                    $formattedUser[$key] = $value;
                }
            }

            $events = is_array($user["events"] ?? null) ? $user["events"] : [];

            $this->formatUserEvents($formattedUser, $events);
            $this->formatUserJoinDuration($formattedUser);
            $this->capDurationsAtRoomDuration($formattedUser);
            $this->usersData[] = $formattedUser;
        }
    }

    /**
     * Format user events.
     *
     * Uses two passes so joined and left events are available before device
     * durations are calculated, regardless of the incoming event order.
     *
     * @param array $user
     * @param array $events
     * @return void
     */
    private function formatUserEvents(array &$user, array $events): void
    {
        // First pass: collect attendance timestamps.
        foreach ($events as $event) {
            if (!is_array($event)) {
                continue;
            }

            $name = $event["name"] ?? null;
            if ($name !== "joined" && $name !== "left") {
                continue;
            }

            $user[$name] = [];
            $values      = is_array($event["values"] ?? null) ? $event["values"] : [];

            foreach ($values as $value) {
                if (!is_array($value)) {
                    continue;
                }

                $timestamp = $value["value"] ?? $value["time"] ?? null;
                if (is_numeric($timestamp)) {
                    $user[$name][] = (int) $timestamp;
                }
            }

            sort($user[$name], SORT_NUMERIC);
        }

        // Second pass: process all other events.
        foreach ($events as $event) {
            if (!is_array($event)) {
                continue;
            }

            $name   = $event["name"] ?? null;
            $values = is_array($event["values"] ?? null) ? $event["values"] : [];

            switch ($name) {
                case "joined":
                case "left":
                    break;

                case "mic_status":
                    $user[$name]       = $this->countStatusStartTypeEvent($values);
                    $user["mic_muted"] = $this->countStatusStartTypeEvent(
                        $values,
                        "ANALYTICS_STATUS_MUTED"
                    );
                    $user["mic_duration"] = $this->getDurationFromEvents(
                        $values,
                        "ANALYTICS_STATUS_STARTED",
                        "ANALYTICS_STATUS_ENDED",
                        $user["left"] ?? []
                    );
                    break;

                case "webcam_status":
                    $user[$name]            = $this->countStatusStartTypeEvent($values);
                    $user["webcam_duration"] = $this->getDurationFromEvents(
                        $values,
                        "ANALYTICS_STATUS_STARTED",
                        "ANALYTICS_STATUS_ENDED",
                        $user["left"] ?? []
                    );
                    break;

                case "screen_share_status":
                    $user[$name] = $this->countStatusStartTypeEvent($values);
                    break;

                case "whiteboard_files":
                case "whiteboard_annotated":
                case "raise_hand":
                case "chat_files":
                case "private_chat":
                case "public_chat":
                case "talked":
                    $user[$name] = max(0, (int) ($event["total"] ?? 0));
                    break;

                case "talked_duration":
                    $milliseconds = max(0, (float) ($event["total"] ?? 0));
                    $user[$name]  = $this->clampDuration(
                        (int) ceil($milliseconds / 1000)
                    );
                    break;

                case "interface_visibility":
                    $user["interface_invisible"] = $this->countStatusStartTypeEvent(
                        $values,
                        "hidden"
                    );
                    break;

                case "connection_quality":
                    $user[$name]["excellent"] = $this->countStatusStartTypeEvent(
                        $values,
                        "excellent"
                    );
                    $user[$name]["good"] = $this->countStatusStartTypeEvent(
                        $values,
                        "good"
                    );
                    $user[$name]["poor"] = $this->countStatusStartTypeEvent(
                        $values,
                        "poor"
                    );
                    break;

                case "voted_poll":
                    $user[$name] = $this->formatUserPollVoted($values);
                    break;
            }
        }
    }

    /**
     * Count how many times an event occurred.
     *
     * @param array  $data
     * @param string $type
     * @return int
     */
    private function countStatusStartTypeEvent(
        array $data,
        string $type = "ANALYTICS_STATUS_STARTED"
    ): int {
        $total = 0;

        foreach ($data as $value) {
            if (!is_array($value)) {
                continue;
            }

            $eventValue = $value["value"] ?? null;
            if (is_string($eventValue) && stripos($eventValue, $type) !== false) {
                $total++;
            }
        }

        return $total;
    }

    /**
     * Format room polls data.
     *
     * @param array $data
     * @return array
     */
    private function formatRoomPolls(array $data): array
    {
        $polls = [];

        foreach ($data as $value) {
            if (!is_array($value) || empty($value["value"])) {
                continue;
            }

            $pollData = json_decode((string) $value["value"], true);
            if (!is_array($pollData) || empty($pollData["poll_id"])) {
                continue;
            }

            $poll = [
                "created"  => isset($value["time"]) && is_numeric($value["time"])
                    ? $this->formatTimestamp($value["time"])
                    : "",
                "question" => $pollData["question"] ?? "",
                "options"  => [],
            ];

            foreach ($pollData["options"] ?? [] as $option) {
                if (!is_array($option) || !isset($option["id"])) {
                    continue;
                }

                $poll["options"][$option["id"]] = [
                    "text"      => $option["text"] ?? "",
                    "responses" => 0,
                ];
            }

            $polls[$pollData["poll_id"]] = $poll;
        }

        return $polls;
    }

    /**
     * Format user's poll voted data.
     *
     * @param array $values
     * @return int
     */
    private function formatUserPollVoted(array $values): int
    {
        $total = 0;

        foreach ($values as $value) {
            if (!is_array($value) || empty($value["value"])) {
                continue;
            }

            $vote = json_decode((string) $value["value"], true);
            if (!is_array($vote) || !isset($vote["poll_id"], $vote["selected_option"])) {
                continue;
            }

            $total++;

            if (
                isset(
                    $this->roomData["polls"][$vote["poll_id"]]
                    ["options"][$vote["selected_option"]]["responses"]
                )
            ) {
                $this->roomData["polls"][$vote["poll_id"]]
                ["options"][$vote["selected_option"]]["responses"]++;
            }
        }

        return $total;
    }

    /**
     * Calculate user join duration using an active/inactive state machine.
     *
     * Merges join and leave events into a single timeline. Only one active
     * session exists at a time — duplicate joins and unmatched leaves are
     * ignored. At most one unfinished session may extend to room end.
     *
     * @param array $user
     * @return void
     */
    private function formatUserJoinDuration(array &$user): void
    {
        $timeline = [];

        foreach ($user["joined"] ?? [] as $time) {
            if (is_numeric($time)) {
                $timeline[] = ["time" => (int) $time, "type" => "join"];
            }
        }

        foreach ($user["left"] ?? [] as $time) {
            if (is_numeric($time)) {
                $timeline[] = ["time" => (int) $time, "type" => "left"];
            }
        }

        $priority = ["left" => 0, "join" => 1];
        usort($timeline, static function (array $a, array $b) use ($priority): int {
            return ($a["time"] <=> $b["time"])
                ?: ($priority[$a["type"]] <=> $priority[$b["type"]]);
        });

        $roomStartMs = $this->getRoomStartMilliseconds();
        $roomEndMs   = $this->getRoomEndMilliseconds();
        $activeSince = null;
        $totalMilliseconds = 0;

        foreach ($timeline as $event) {
            $time = $event["time"];

            if ($roomStartMs !== null && $time < $roomStartMs) {
                continue;
            }
            if ($roomEndMs !== null && $time > $roomEndMs) {
                continue;
            }

            if ($event["type"] === "join") {
                if ($activeSince === null) {
                    $activeSince = $time;
                }
                continue;
            }

            if ($activeSince === null) {
                continue;
            }

            if ($time > $activeSince) {
                $totalMilliseconds += $time - $activeSince;
            }
            $activeSince = null;
        }

        if ($activeSince !== null && $roomEndMs !== null && $roomEndMs > $activeSince) {
            $totalMilliseconds += $roomEndMs - $activeSince;
        }

        $user["duration"] = $this->clampDuration(
            (int) ceil($totalMilliseconds / 1000)
        );
    }

    /**
     * Cap per-user duration fields at the authoritative room duration limit.
     *
     * @param array $user
     * @return void
     */
    private function capDurationsAtRoomDuration(array &$user): void
    {
        foreach (["duration", "talked_duration", "mic_duration", "webcam_duration"] as $field) {
            if (isset($user[$field]) && is_numeric($user[$field])) {
                $user[$field] = $this->clampDuration($user[$field]);
            }
        }
    }

    /**
     * Format timestamp to user readable format.
     *
     * @param mixed $timestamp
     * @param bool  $ms
     * @return string
     */
    public function formatTimestamp($timestamp, bool $ms = true): string
    {
        if (!is_numeric($timestamp)) {
            return "";
        }

        $value = $ms
            ? (int) floor((float) $timestamp / 1000)
            : (int) floor((float) $timestamp);

        $date = new DateTime();
        $date->setTimestamp($value);
        $date->setTimezone($this->timezone);

        return $date->format("d-m-Y H:i:s P");
    }

    /**
     * Format seconds to H:i:s format.
     *
     * @param mixed $seconds
     * @return string
     */
    public function formatSecondsToTime($seconds): string
    {
        $seconds         = max(0, (int) round((float) $seconds));
        $hours           = intdiv($seconds, 3600);
        $minutes         = intdiv($seconds % 3600, 60);
        $remainingSeconds = $seconds % 60;

        return sprintf("%02d:%02d:%02d", $hours, $minutes, $remainingSeconds);
    }

    /**
     * Get room start time in milliseconds, or null if unavailable.
     *
     * @return int|null
     */
    private function getRoomStartMilliseconds(): ?int
    {
        $roomCreation = $this->rawData["room"]["room_creation"] ?? null;

        if (!is_numeric($roomCreation) || (int) $roomCreation <= 0) {
            return null;
        }

        return (int) $roomCreation * 1000;
    }

    /**
     * Get room end time in milliseconds, or null if unavailable.
     *
     * @return int|null
     */
    private function getRoomEndMilliseconds(): ?int
    {
        $roomEnded = $this->rawData["room"]["room_ended"] ?? null;

        if (!is_numeric($roomEnded) || (int) $roomEnded <= 0) {
            return null;
        }

        return (int) $roomEnded * 1000;
    }

    /**
     * Returns the authoritative room duration limit in seconds.
     *
     * Uses the smaller of room_duration and room_ended - room_creation
     * to guard against inconsistent data.
     *
     * @return int 0 if no valid limit is available.
     */
    private function getRoomDurationLimit(): int
    {
        $limits       = [];
        $roomDuration = $this->rawData["room"]["room_duration"] ?? null;

        if (is_numeric($roomDuration) && (int) $roomDuration > 0) {
            $limits[] = (int) $roomDuration;
        }

        $roomStartMs = $this->getRoomStartMilliseconds();
        $roomEndMs   = $this->getRoomEndMilliseconds();

        if ($roomStartMs !== null && $roomEndMs !== null && $roomEndMs > $roomStartMs) {
            $timestampDuration = (int) floor(($roomEndMs - $roomStartMs) / 1000);
            if ($timestampDuration > 0) {
                $limits[] = $timestampDuration;
            }
        }

        return empty($limits) ? 0 : min($limits);
    }

    /**
     * Clamp a duration value to the valid range [0, room limit].
     *
     * When no trustworthy limit exists the raw duration is returned
     * unchanged — we never zero out data just because room metadata
     * is incomplete.
     *
     * @param mixed $duration
     * @return int
     */
    private function clampDuration($duration): int
    {
        $duration  = max(0, (int) $duration);
        $roomLimit = $this->getRoomDurationLimit();

        return $roomLimit > 0 ? min($duration, $roomLimit) : $duration;
    }

    /**
     * Calculate device duration from start/end and user-leave events.
     *
     * Builds a timeline from start, end, and user leave events. A pending
     * device session is closed by the earliest of: matching end event,
     * user leave, or room end.
     *
     * Uses stripos for prefix matching so values like
     * "ANALYTICS_STATUS_STARTED:node_01" are correctly recognised.
     *
     * @param array  $events       Device status events (mic_status, webcam_status, etc.)
     * @param string $startStatus  Value prefix for start events
     * @param string $endStatus    Value prefix for end events
     * @param array  $leaveEvents  User leave timestamps (ms)
     * @return int   Duration in seconds
     */
    private function getDurationFromEvents(
        array $events,
        string $startStatus = "ANALYTICS_STATUS_STARTED",
        string $endStatus = "ANALYTICS_STATUS_ENDED",
        array $leaveEvents = []
    ): int {
        $timeline = [];

        foreach ($events as $event) {
            if (
                !is_array($event)
                || !isset($event["time"], $event["value"])
                || !is_numeric($event["time"])
            ) {
                continue;
            }

            $value = (string) $event["value"];
            if (stripos($value, $startStatus) === 0) {
                $timeline[] = ["time" => (int) $event["time"], "type" => "start"];
            } elseif (stripos($value, $endStatus) === 0) {
                $timeline[] = ["time" => (int) $event["time"], "type" => "end"];
            }
        }

        foreach ($leaveEvents as $time) {
            if (is_numeric($time)) {
                $timeline[] = ["time" => (int) $time, "type" => "leave"];
            }
        }

        $priority = ["end" => 0, "leave" => 0, "start" => 1];
        usort($timeline, static function (array $a, array $b) use ($priority): int {
            return ($a["time"] <=> $b["time"])
                ?: ($priority[$a["type"]] <=> $priority[$b["type"]]);
        });

        $roomStartMs = $this->getRoomStartMilliseconds();
        $roomEndMs   = $this->getRoomEndMilliseconds();
        $pendingStart = null;
        $totalMilliseconds = 0;

        foreach ($timeline as $event) {
            $time = $event["time"];

            if ($roomStartMs !== null && $time < $roomStartMs) {
                continue;
            }
            if ($roomEndMs !== null && $time > $roomEndMs) {
                continue;
            }

            if ($event["type"] === "start") {
                if ($pendingStart === null) {
                    $pendingStart = $time;
                }
                continue;
            }

            if ($pendingStart === null) {
                continue;
            }

            if ($time > $pendingStart) {
                $totalMilliseconds += $time - $pendingStart;
            }
            $pendingStart = null;
        }

        if ($pendingStart !== null && $roomEndMs !== null && $roomEndMs > $pendingStart) {
            $totalMilliseconds += $roomEndMs - $pendingStart;
        }

        return $this->clampDuration(
            (int) ceil($totalMilliseconds / 1000)
        );
    }
}
