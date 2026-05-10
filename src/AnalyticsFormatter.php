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

use DateTimeZone;

/**
 * A simple class to format analytics data to make it more human-readable and to perform calculations.
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
        "mic_muted", "mic_duration", "talked", "talked_duration", "webcam_status", "webcam_duration", "raise_hand",
        "voted_poll", "whiteboard_annotated", "whiteboard_files", "screen_share_status",
        "public_chat", "private_chat", "chat_files", "interface_invisible", "connection_quality",
    ];

    /**
     * @param array $rawData Raw analytics data from plugNmeet-server
     * @param string $userTimezone User's timezone
     */
    public function __construct(array $rawData, string $userTimezone = 'UTC')
    {
        $this->rawData = $rawData;
        $this->timezone = new DateTimeZone($userTimezone);

        $this->formatRoomData();
        $this->formatUsersData();
    }

    /**
     * Get formatted analytics data
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
     * Get raw analytics data
     * @return array
     */
    public function getRawAnalyticsData(): array
    {
        return $this->rawData;
    }

    /**
     * Get user fields
     * @return string[]
     */
    public function getUserFields(): array
    {
        return $this->userFields;
    }

    /**
     * Get room fields
     * @return string[]
     */
    public function getRoomFields(): array
    {
        return $this->roomFields;
    }

    /**
     * Format basic room data
     * @return void
     */
    private function formatRoomData(): void
    {
        foreach ($this->rawData["room"] as $key => $data) {
            if ($key !== "events") {
                if ($key === "room_creation" || $key === "room_ended") {
                    $data = $this->formatTimestamp($data, false);
                }
                $this->roomData[$key] = $data;
            }
        }
        if (isset($this->rawData["room"]["events"])) {
            $this->formatRoomEvents($this->rawData["room"]["events"]);
        }
    }

    /**
     * Format room events
     * @param array $events
     * @return void
     */
    private function formatRoomEvents(array $events): void
    {
        foreach ($events as $event) {
            switch ($event["name"]) {
                case "recording_status":
                case "rtmp_status":
                case "external_media_player_status":
                case "external_display_link_status":
                case "etherpad_status":
                    $this->roomData[$event["name"]] = $this->countStatusStartTypeEvent($event["values"]);
                    break;
                case "ingress_created":
                case "breakout_room":
                    $this->roomData[$event["name"]] = $event["total"];
                    break;
                case "whiteboard_files":
                    $this->roomData[$event["name"]] = $event["values"];
                    break;
                case "poll_added":
                    $this->roomData["polls"] = $this->formatRoomPolls($event["values"]);
                    break;
            }
        }
    }

    /**
     * Format user data
     * @return void
     */
    private function formatUsersData(): void
    {
        foreach ($this->rawData["users"] as $user) {
            $u = [];
            foreach ($user as $key => $val) {
                if ($key !== "events") {
                    $u[$key] = $val;
                }
            }
            $this->formatUserEvents($u, $user["events"]);
            $this->formatUserJoinDuration($u);
            $this->usersData[] = $u;
        }
    }

    /**
     * Format user events
     * @param array $user
     * @param array $events
     * @return void
     */
    private function formatUserEvents(array &$user, array $events): void
    {
        foreach ($events as $event) {
            switch ($event["name"]) {
                case "mic_status":
                    $user[$event["name"]] = $this->countStatusStartTypeEvent($event["values"]);
                    $user["mic_muted"]    = $this->countStatusStartTypeEvent($event["values"], "ANALYTICS_STATUS_MUTED");
                    $user['mic_duration'] = $this->getDurationFromEvents($event['values']);
                    break;
                case "webcam_status":
                    $user[$event["name"]] = $this->countStatusStartTypeEvent($event["values"]);
                    $user['webcam_duration'] = $this->getDurationFromEvents($event['values']);
                    break;
                case "screen_share_status":
                    $user[$event["name"]] = $this->countStatusStartTypeEvent($event["values"]);
                    break;
                case "whiteboard_files":
                case "whiteboard_annotated":
                case "raise_hand":
                case "chat_files":
                case "private_chat":
                case "public_chat":
                case "talked":
                    $user[$event["name"]] = $event["total"];
                    break;
                case "talked_duration":
                    $user[$event["name"]] = (int)ceil((float)$event["total"] / 1000); // Store in seconds.
                    break;
                case "joined":
                case "left":
                    $user[$event["name"]] = array_map(function ($val) {
                        return (int)$val["value"];
                    }, $event["values"]);
                    sort($user[$event["name"]], SORT_NUMERIC);
                    break;
                case "interface_visibility":
                    $user["interface_invisible"] = $this->countStatusStartTypeEvent($event["values"], "hidden");
                    break;
                case "connection_quality":
                    $user[$event["name"]]["excellent"] = $this->countStatusStartTypeEvent($event["values"], "excellent");
                    $user[$event["name"]]["good"]      = $this->countStatusStartTypeEvent($event["values"], "good");
                    $user[$event["name"]]["poor"]      = $this->countStatusStartTypeEvent($event["values"], "poor");
                    break;
                case "voted_poll":
                    $user[$event["name"]] = $this->formatUserPollVoted($event["values"]);
                    break;
            }
        }
    }

    /**
     * Count how many times an event occurred
     * @param array $data
     * @param string $type
     * @return int
     */
    private function countStatusStartTypeEvent(array $data, string $type = "ANALYTICS_STATUS_STARTED"): int
    {
        $total = 0;
        foreach ($data as $val) {
            if (!empty($val["value"])) {
                if (stripos($type, $val["value"]) !== false) {
                    $total++;
                }
            }
        }

        return $total;
    }

    /**
     * Format room polls data
     * @param array $data
     * @return array
     */
    private function formatRoomPolls(array $data): array
    {
        $polls = [];
        foreach ($data as $val) {
            if (empty($val["value"])) {
                continue;
            }
            $p = json_decode($val["value"], true);

            $poll = [
                "created"  => $this->formatTimestamp($val["time"]),
                "question" => $p["question"],
            ];

            foreach ($p["options"] as $opt) {
                $poll["options"][$opt["id"]] = [
                    "text"      => $opt["text"],
                    "responses" => 0,
                ];
            }
            $polls[$p["poll_id"]] = $poll;
        }

        return $polls;
    }

    /**
     * Format user's poll voted data
     * @param array $values
     * @return int
     */
    private function formatUserPollVoted(array $values): int
    {
        $total = 0;
        if (empty($values)) {
            return $total;
        }
        foreach ($values as $val) {
            $total++;
            $vote = json_decode($val["value"], true);
            if (isset($this->roomData["polls"][$vote["poll_id"]])) {
                $this->roomData["polls"][$vote["poll_id"]]["options"][$vote["selected_option"]]["responses"] += 1;
            }
        }

        return $total;
    }

    /**
     * Format user join duration
     * @param array $user
     * @return void
     */
    private function formatUserJoinDuration(array &$user): void
    {
        if (empty($user["joined"])) {
            $user["duration"] = 0;
            return;
        }

        $totalDuration = 0;
        $roomEndedTimestamp = null;

        if (isset($this->rawData["room"]["room_ended"])) {
            $roomEndedTimestamp = (float)$this->rawData["room"]["room_ended"] * 1000;
        }

        $joinedEvents = $user["joined"];
        $leftEvents = $user["left"] ?? [];

        sort($joinedEvents);
        sort($leftEvents);

        $leftIndex = 0;
        foreach ($joinedEvents as $joinTime) {
            $leaveTime = null;

            while ($leftIndex < count($leftEvents) && $leftEvents[$leftIndex] <= $joinTime) {
                $leftIndex++;
            }

            if ($leftIndex < count($leftEvents)) {
                $leaveTime = (float)$leftEvents[$leftIndex];
                $leftIndex++;
            } else {
                $leaveTime = (float)$roomEndedTimestamp;
            }

            if ($leaveTime > (float)$joinTime) {
                $totalDuration += ($leaveTime - (float)$joinTime);
            }
        }

        $user["duration"] = (int)ceil($totalDuration / 1000); // Store in seconds.
    }

    /**
     * Format timestamp to user readable format
     * @param $timestamp
     * @param bool $ms
     * @return string
     */
    public function formatTimestamp($timestamp, bool $ms = true): string
    {
        $t = new \DateTime();
        $val = $ms ? (int)floor((float)$timestamp / 1000) : (int)floor((float)$timestamp);
        $t->setTimestamp($val);
        $t->setTimezone($this->timezone);

        return $t->format("d-m-Y H:i:s P");
    }

    /**
     * Format seconds to H:i:s format
     * @param $seconds
     * @return string
     */
    public function formatSecondsToTime($seconds): string
    {
        $s = (int)round((float)$seconds);
        $h = intdiv($s, 3600);
        $m = intdiv($s % 3600, 60);
        $sec = $s % 60;

        return sprintf("%02d:%02d:%02d", (int)$h, (int)$m, (int)$sec);
    }

    /**
     * Get duration from events
     * @param array $events
     * @param string $startStatus
     * @param string $endStatus
     * @return int
     */
    private function getDurationFromEvents(array $events, string $startStatus = 'ANALYTICS_STATUS_STARTED', string $endStatus = 'ANALYTICS_STATUS_ENDED'): int
    {
        if (empty($events)) {
            return 0;
        }

        usort($events, function ($a, $b) {
            return (int)$a['time'] <=> (int)$b['time'];
        });

        $totalDuration = 0;
        $startedTime = 0;

        foreach ($events as $event) {
            if ($event['value'] === $startStatus) {
                $startedTime = (float)$event['time'];
            } elseif ($event['value'] === $endStatus && $startedTime > 0) {
                $totalDuration += ((float)$event['time'] - $startedTime);
                $startedTime = 0;
            }
        }

        if ($startedTime > 0 && isset($this->rawData["room"]["room_ended"])) {
            $roomEndedTimestamp = (float)$this->rawData["room"]["room_ended"] * 1000;
            if ($roomEndedTimestamp > $startedTime) {
                $totalDuration += ($roomEndedTimestamp - $startedTime);
            }
        }

        return (int)ceil($totalDuration / 1000);
    }
}
