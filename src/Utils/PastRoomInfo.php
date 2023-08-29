<?php

/*
 * Copyright (c) 2022 MynaParrot
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

namespace Mynaparrot\Plugnmeet\Utils;

/**
 *
 */
class PastRoomInfo
{
    /**
     * @var object|null
     */
    protected $roomInfo = null;

    /**
     * @param object $roomInfo
     */
    public function __construct(object $roomInfo)
    {
        $this->roomInfo = $roomInfo;
    }

    /**
     * @return string|null
     */
    public function getRoomTitle(): ?string
    {
        if (isset($this->roomInfo->room_title)) {
            return $this->roomInfo->room_title;
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getRoomId(): ?string
    {
        if (isset($this->roomInfo->room_id)) {
            return $this->roomInfo->room_id;
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getRoomSid(): ?string
    {
        if (isset($this->roomInfo->sid)) {
            return $this->roomInfo->sid;
        }
        return null;
    }

    /**
     * @return int
     */
    public function getJoinedParticipants(): int
    {
        if (isset($this->roomInfo->joined_participants)) {
            return $this->roomInfo->joined_participants;
        }
        return 0;
    }

    /**
     * @return string|null
     */
    public function getWebhookUrl(): ?string
    {
        if (isset($this->roomInfo->webhook_url)) {
            return $this->roomInfo->webhook_url;
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getCreatedDate(): ?string
    {
        if (isset($this->roomInfo->created)) {
            return $this->roomInfo->created;
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getEndedDate(): ?string
    {
        if (isset($this->roomInfo->ended)) {
            return $this->roomInfo->ended;
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getAnalyticsFileId(): ?string
    {
        if (isset($this->roomInfo->analytics_file_id)) {
            return $this->roomInfo->analytics_file_id;
        }
        return null;
    }
}
