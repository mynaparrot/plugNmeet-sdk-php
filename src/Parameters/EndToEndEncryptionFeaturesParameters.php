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

class EndToEndEncryptionFeaturesParameters
{
    /**
     * @var bool
     */
    protected $isEnable = false;
    /**
     * @var bool
     */
    protected $includedChatMessages = false;
    /**
     * @var bool
     */
    protected $includedWhiteboard = false;

    public function __construct()
    {
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->isEnable;
    }

    /**
     * @param bool $isEnable
     */
    public function setIsEnable(bool $isEnable): void
    {
        $this->isEnable = filter_var($isEnable, FILTER_VALIDATE_BOOLEAN);
    }

    public function isIncludedChatMessages(): bool
    {
        return $this->includedChatMessages;
    }

    public function setIncludedChatMessages(bool $includedChatMessages): void
    {
        $this->includedChatMessages = filter_var($includedChatMessages, FILTER_VALIDATE_BOOLEAN);
    }

    public function isIncludedWhiteboard(): bool
    {
        return $this->includedWhiteboard;
    }

    public function setIncludedWhiteboard(bool $includedWhiteboard): void
    {
        $this->includedWhiteboard = filter_var($includedWhiteboard, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return array
     */
    public function buildBody(): array
    {
        return array(
            "is_enabled" => $this->isEnable(),
            "included_chat_messages" => $this->isIncludedChatMessages(),
            "included_whiteboard" => $this->isIncludedWhiteboard(),
        );
    }
}
