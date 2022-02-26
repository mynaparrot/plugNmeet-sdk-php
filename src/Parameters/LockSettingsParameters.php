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
class LockSettingsParameters
{

    /**
     * @var bool
     */
    protected $lockMicrophone;
    /**
     * @var bool
     */
    protected $lockWebcam;
    /**
     * @var bool
     */
    protected $lockScreenSharing;
    /**
     * @var bool
     */
    protected $lockChat;
    /**
     * @var bool
     */
    protected $lockChatSendMessage;
    /**
     * @var bool
     */
    protected $lockChatFileShare;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return bool
     */
    public function isLockMicrophone()
    {
        return $this->lockMicrophone;
    }

    /**
     * @param bool $lockMicrophone
     */
    public function setLockMicrophone($lockMicrophone)
    {
        $this->lockMicrophone = filter_var($lockMicrophone, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isLockWebcam()
    {
        return $this->lockWebcam;
    }

    /**
     * @param bool $lockWebcam
     */
    public function setLockWebcam($lockWebcam)
    {
        $this->lockWebcam = filter_var($lockWebcam, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isLockScreenSharing()
    {
        return $this->lockScreenSharing;
    }

    /**
     * @param bool $lockScreenSharing
     */
    public function setLockScreenSharing($lockScreenSharing)
    {
        $this->lockScreenSharing = filter_var($lockScreenSharing, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isLockChat()
    {
        return $this->lockChat;
    }

    /**
     * @param bool $lockChat
     */
    public function setLockChat($lockChat)
    {
        $this->lockChat = filter_var($lockChat, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isLockChatSendMessage()
    {
        return $this->lockChatSendMessage;
    }

    /**
     * @param bool $lockChatSendMessage
     */
    public function setLockChatSendMessage($lockChatSendMessage)
    {
        $this->lockChatSendMessage = filter_var($lockChatSendMessage, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isLockChatFileShare()
    {
        return $this->lockChatFileShare;
    }

    /**
     * @param bool $lockChatFileShare
     */
    public function setLockChatFileShare($lockChatFileShare)
    {
        $this->lockChatFileShare = filter_var($lockChatFileShare, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return array
     */
    public function buildBody()
    {
        return array(
            'lock_microphone' => $this->lockMicrophone,
            'lock_webcam' => $this->lockWebcam,
            'lock_screen_sharing' => $this->lockScreenSharing,
            'lock_chat' => $this->lockChat,
            'lock_chat_send_message' => $this->lockChatSendMessage,
            'lock_chat_file_share' => $this->lockChatFileShare,
        );
    }
}
