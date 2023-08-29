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
class RecordingInfo
{
    /**
     * @var object|null
     */
    protected $recording = null;

    /**
     * @param object $recording
     */
    public function __construct(object $recording)
    {
        $this->recording = $recording;
    }

    /**
     * @return string|null
     */
    public function getRecordId(): ?string
    {
        if (isset($this->recording->record_id)) {
            return $this->recording->record_id;
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getRoomId(): ?string
    {
        if (isset($this->recording->room_id)) {
            return $this->recording->room_id;
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getRoomSid(): ?string
    {
        if (isset($this->recording->room_sid)) {
            return $this->recording->room_sid;
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getFilePath(): ?string
    {
        if (isset($this->recording->file_path)) {
            return $this->recording->file_path;
        }
        return null;
    }

    /**
     * @return float|null
     */
    public function getFileSize(): ?float
    {
        if (isset($this->recording->file_size)) {
            return $this->recording->file_size;
        }
        return null;
    }

    /**
     * @return int|null
     */
    public function getCreationTime(): ?int
    {
        if (isset($this->recording->creation_time)) {
            return $this->recording->creation_time;
        }
        return null;
    }

    /**
     * @return int|null
     */
    public function getRoomCreationTime(): ?int
    {
        if (isset($this->recording->room_creation_time)) {
            return $this->recording->room_creation_time;
        }
        return null;
    }
}
