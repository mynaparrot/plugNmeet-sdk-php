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
use Mynaparrot\Plugnmeet\Utils\RecordingInfo;

/**
 *
 */
class RecordingInfoResponse extends BaseResponse
{
    /**
     * @return RecordingInfo|null
     */
    public function getRecordingInfo(): ?RecordingInfo
    {
        if (isset($this->rawResponse->recording_info)) {
            return new RecordingInfo($this->rawResponse->recording_info);
        }

        return null;
    }

    /**
     * @return PastRoomInfo|null
     */
    public function getRoomInfo(): ?PastRoomInfo
    {
        if (isset($this->rawResponse->room_info)) {
            return new PastRoomInfo($this->rawResponse->room_info);
        }

        return null;
    }
}
