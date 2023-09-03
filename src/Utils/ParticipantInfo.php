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
class ParticipantInfo
{
    /**
     * @var object|null
     */
    protected $participantInfo = null;

    /**
     * @param object $participantInfo
     */
    public function __construct(object $participantInfo)
    {
        $this->participantInfo = $participantInfo;
    }

    /**
     * @return string|null
     */
    public function getSid(): ?string
    {
        if (isset($this->participantInfo->sid)) {
            return $this->participantInfo->sid;
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getIdentity(): ?string
    {
        if (isset($this->participantInfo->identity)) {
            return $this->participantInfo->identity;
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getState(): ?string
    {
        if (isset($this->participantInfo->state)) {
            return $this->participantInfo->state;
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getMetadata(): ?string
    {
        if (isset($this->participantInfo->metadata)) {
            return $this->participantInfo->metadata;
        }
        return null;
    }

    /**
     * @return int|null
     */
    public function getJoinedAt(): ?int
    {
        if (isset($this->participantInfo->joined_at)) {
            return $this->participantInfo->joined_at;
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        if (isset($this->participantInfo->name)) {
            return $this->participantInfo->name;
        }
        return null;
    }

    /**
     * @return int|null
     */
    public function getVersion(): ?int
    {
        if (isset($this->participantInfo->version)) {
            return $this->participantInfo->version;
        }
        return null;
    }

    /**
     * @return mixed|null
     */
    public function getPermission()
    {
        if (isset($this->participantInfo->permission)) {
            return $this->participantInfo->permission;
        }
        return null;
    }

    /**
     * @return array
     */
    public function getTracks()
    {
        if (isset($this->participantInfo->tracks)) {
            return $this->participantInfo->tracks;
        }

        return [];
    }
}
