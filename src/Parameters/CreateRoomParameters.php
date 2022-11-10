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

namespace Mynaparrot\Plugnmeet\Parameters;

/**
 *
 */
class CreateRoomParameters
{
    /**
     * @var string
     */
    protected $roomId;
    /**
     * @var int
     */
    protected $emptyTimeout = 0;
    /**
     * @var int
     */
    protected $maxParticipants = 0;
    /**
     * @var RoomMetadataParameters
     */
    protected $roomMetadata;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getRoomId(): string
    {
        return $this->roomId;
    }

    /**
     * @param string $roomId
     */
    public function setRoomId(string $roomId)
    {
        $this->roomId = $roomId;
    }

    /**
     * @return int
     */
    public function getEmptyTimeout(): int
    {
        return $this->emptyTimeout;
    }

    /**
     * @param int $emptyTimeout
     */
    public function setEmptyTimeout(int $emptyTimeout)
    {
        $this->emptyTimeout = $emptyTimeout;
    }

    /**
     * @return int
     */
    public function getMaxParticipants(): int
    {
        return $this->maxParticipants;
    }

    /**
     * @param int $maxParticipants
     */
    public function setMaxParticipants(int $maxParticipants)
    {
        $this->maxParticipants = $maxParticipants;
    }

    /**
     * @return RoomMetadataParameters
     */
    public function getRoomMetadata(): RoomMetadataParameters
    {
        return $this->roomMetadata;
    }

    /**
     * @param RoomMetadataParameters $roomMetadata
     */
    public function setRoomMetadata(RoomMetadataParameters $roomMetadata)
    {
        $this->roomMetadata = $roomMetadata;
    }

    /**
     * @return array
     */
    public function buildBody(): array
    {
        $body = array(
            "room_id" => $this->getRoomId(),
        );

        if ($this->maxParticipants > 0) {
            $body['max_participants'] = $this->getMaxParticipants();
        }

        if ($this->emptyTimeout > 0) {
            $body['empty_timeout'] = $this->getEmptyTimeout();
        }

        if ($this->roomMetadata !== null) {
            $body['metadata'] = $this->getRoomMetadata()->buildBody();
        }

        return $body;
    }
}
