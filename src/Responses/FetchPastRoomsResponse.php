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

namespace Mynaparrot\Plugnmeet\Responses;

use Mynaparrot\Plugnmeet\Utils\PastRoomInfo;

/**
 *
 */
class FetchPastRoomsResponse extends BaseResponse
{
    /**
     * @return int
     */
    public function getTotalRooms(): int
    {
        if (!isset($this->rawResponse->result->total_rooms)) {
            return 0;
        }
        return $this->rawResponse->result->total_rooms;
    }

    /**
     * @return int
     */
    public function getFrom(): int
    {
        if (!isset($this->rawResponse->result->from)) {
            return 0;
        }
        return $this->rawResponse->result->from;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        if (!isset($this->rawResponse->result->limit)) {
            return 20;
        }
        return $this->rawResponse->result->limit;
    }

    /**
     * @return string
     */
    public function getOrderBy(): string
    {
        if (!isset($this->rawResponse->result->order_by)) {
            return "DESC";
        }
        return $this->rawResponse->result->order_by;
    }

    /**
     * @return PastRoomInfo[]
     */
    public function getRooms(): array
    {
        $rooms = [];
        if (!isset($this->rawResponse->result)) {
            return $rooms;
        }

        if (count($this->rawResponse->result->rooms_list) > 0) {
            foreach ($this->rawResponse->result->rooms_list as $room) {
                $rooms[] = new PastRoomInfo($room);
            }
        }

        return $rooms;
    }
}
