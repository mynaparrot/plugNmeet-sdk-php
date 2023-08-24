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
class WhiteboardFeaturesParameters
{
    /**
     * @var bool
     */
    protected $allowedWhiteboard = true;
    /**
     * @var string|null
     */
    protected $preloadFile = null;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return bool
     */
    public function isAllowedWhiteboard(): bool
    {
        return $this->allowedWhiteboard;
    }

    /**
     * @param bool $allowedWhiteboard
     */
    public function setAllowedWhiteboard(bool $allowedWhiteboard): void
    {
        $this->allowedWhiteboard = filter_var($allowedWhiteboard, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return string|null
     */
    public function getPreloadFile(): ?string
    {
        return $this->preloadFile;
    }

    /**
     * @param string $preloadFileUrl
     * Should be direct http/https link & no redirection
     * example: https://mydomain.com/text_book.pdf
     */
    public function setPreloadFile(string $preloadFileUrl): void
    {
        if (filter_var($preloadFileUrl, FILTER_VALIDATE_URL) === false) {
            return;
        }

        $context = stream_context_create(
            [
                'http' => array(
                    'method' => 'HEAD',
                    'follow_location' => 0
                )
            ]
        );
        $headers = get_headers($preloadFileUrl, true, $context);
        if (
            $headers &&
            strpos($headers[0], '200') !== false &&
            isset($headers["Content-Length"]) &&
            (int)$headers["Content-Length"] > 1
        ) {
            $this->preloadFile = $preloadFileUrl;
        }
    }

    /**
     * @return array
     */
    public function buildBody(): array
    {
        $body = array(
            "allowed_whiteboard" => $this->isAllowedWhiteboard(),
        );

        if (!empty($this->preloadFile)) {
            $body["preload_file"] = $this->getPreloadFile();
        }

        return $body;
    }
}
