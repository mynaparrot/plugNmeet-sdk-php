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
class UserMetadataParameters
{
    /**
     * @var string
     */
    protected $profilePic;
    /**
     * @var bool
     */
    protected $recordWebcam = true;
    /**
     * @var LockSettingsParameters
     */
    protected $lockSettings;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getProfilePic(): string
    {
        return $this->profilePic;
    }

    /**
     * @param string $profilePic
     */
    public function setProfilePic(string $profilePic)
    {
        $this->profilePic = $profilePic;
    }

    /**
     * @return bool
     */
    public function isRecordWebcam(): bool
    {
        return $this->recordWebcam;
    }

    /**
     * @param bool $recordWebcam
     */
    public function setRecordWebcam(bool $recordWebcam): void
    {
        $this->recordWebcam = filter_var($recordWebcam, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return LockSettingsParameters
     */
    public function getLockSettings(): LockSettingsParameters
    {
        return $this->lockSettings;
    }

    /**
     * @param LockSettingsParameters $lockSettings
     */
    public function setLockSettings(LockSettingsParameters $lockSettings)
    {
        $this->lockSettings = $lockSettings;
    }

    /**
     * @return array
     */
    public function buildBody(): array
    {
        $body = array(
            "record_webcam" => $this->isRecordWebcam()
        );

        if (!empty($this->profilePic)) {
            $body["profile_pic"] = $this->getProfilePic();
        }

        if ($this->lockSettings !== null) {
            $body["lock_settings"] = $this->getLockSettings()->buildBody();
        }

        return $body;
    }
}
