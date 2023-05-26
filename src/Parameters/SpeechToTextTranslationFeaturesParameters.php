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

class SpeechToTextTranslationFeaturesParameters
{
    /**
     * @var bool
     */
    protected $isAllow = true;
    /**
     * @var bool
     */
    protected $isAllowTranslation = true;

    public function __construct()
    {
    }

    /**
     * @return bool
     */
    public function isAllow(): bool
    {
        return $this->isAllow;
    }

    /**
     * @param bool $isAllow
     */
    public function setIsAllow(bool $isAllow): void
    {
        $this->isAllow = filter_var($isAllow, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isAllowTranslation(): bool
    {
        return $this->isAllowTranslation;
    }

    /**
     * @param bool $isAllowTranslation
     */
    public function setIsAllowTranslation(bool $isAllowTranslation): void
    {
        $this->isAllowTranslation = filter_var($isAllowTranslation, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return array
     */
    public function buildBody(): array
    {
        return array(
            "is_allow" => $this->isAllow(),
            "is_allow_translation" => $this->isAllowTranslation()
        );
    }
}