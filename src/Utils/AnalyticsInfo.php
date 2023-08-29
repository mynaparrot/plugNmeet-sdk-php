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

class AnalyticsInfo
{
    /**
     * @var object|null
     */
    protected $analyticsInfo = null;

    /**
     * @param object $analyticsInfo
     */
    public function __construct(object $analyticsInfo)
    {
        $this->analyticsInfo = $analyticsInfo;
    }

    /**
     * @return string|null
     */
    public function getRoomId(): ?string
    {
        if (isset($this->analyticsInfo->room_id)) {
            return $this->analyticsInfo->room_id;
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getFileId(): ?string
    {
        if (isset($this->analyticsInfo->file_id)) {
            return $this->analyticsInfo->file_id;
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getFileName(): ?string
    {
        if (isset($this->analyticsInfo->file_name)) {
            return $this->analyticsInfo->file_name;
        }
        return null;
    }

    /**
     * @return float|null
     */
    public function getFileSize(): ?float
    {
        if (isset($this->analyticsInfo->file_size)) {
            return $this->analyticsInfo->file_size;
        }
        return null;
    }

    /**
     * @return int|null
     */
    public function getCreationTime(): ?int
    {
        if (isset($this->analyticsInfo->creation_time)) {
            return $this->analyticsInfo->creation_time;
        }
        return null;
    }

    /**
     * @return int|null
     */
    public function getRoomCreationTime(): ?int
    {
        if (isset($this->analyticsInfo->room_creation_time)) {
            return $this->analyticsInfo->room_creation_time;
        }
        return null;
    }
}
