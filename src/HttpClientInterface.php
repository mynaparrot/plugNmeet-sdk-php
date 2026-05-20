<?php

/*
 * Copyright (c) 2022 onward MynaParrot
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

namespace Mynaparrot\Plugnmeet;

interface HttpClientInterface
{
    /**
     * Sends a POST request to the specified URL with the given data.
     *
     * @param string $url The URL to send the request to.
     * @param string $body The raw request body.
     * @param array $headers Optional headers to send with the request.
     * @return string The response body as a string.
     * @throws \Exception If the request fails.
     */
    public function post(string $url, string $body, array $headers = []): string;

    /**
     * Sends a multipart/form-data request to the specified URL with the given data.
     *
     * @param string $url The URL to send the request to.
     * @param array $multipart The multipart data to send.
     * @param array $headers Optional headers to send with the request.
     * @return string The response body as a string.
     */
    public function uploadFile(string $url, array $multipart, array $headers = []): string;
}
