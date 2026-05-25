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

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

/**
 * An HTTP client that uses Guzzle to send requests.
 */
class GuzzleHttpClient implements HttpClientInterface
{
    /**
     * @var Client
     */
    protected Client $guzzleClient;

    /**
     * @param int $timeout
     * @param bool $verifySSL
     */
    public function __construct(int $timeout = 60, bool $verifySSL = true)
    {
        $this->guzzleClient = new Client([
                                             'timeout' => $timeout,
                                             'verify' => $verifySSL,
                                         ]);
    }

    /**
     * @param string $url The full URL for the request
     * @param string $body
     * @param array $headers
     * @return string
     * @throws Exception
     */
    public function post(string $url, string $body, array $headers = []): string
    {
        try {
            $response = $this->guzzleClient->post($url, [
                'headers' => $headers,
                'body' => $body,
            ]);

            return $response->getBody()->getContents();
        } catch (ConnectException $e) {
            // Extract the core error message from cURL errors
            $message = $e->getMessage();
            if (preg_match('/cURL error \d+: (.*) \(see/', $message, $matches)) {
                throw new Exception($matches[1]);
            } else {
                throw new Exception('Connection Error: ' . $message);
            }
        } catch (RequestException $e) {
            throw new Exception($this->handleRequestException($e));
        } catch (GuzzleException $e) {
            throw new Exception('Guzzle Error: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param string $url
     * @param array $multipart
     * @param array $headers
     * @return string
     * @throws Exception
     */
    public function uploadFile(string $url, array $multipart, array $headers = []): string
    {
        try {
            $response = $this->guzzleClient->post($url, [
                'headers' => $headers,
                'multipart' => $multipart,
            ]);

            return $response->getBody()->getContents();
        } catch (ConnectException $e) {
            $message = $e->getMessage();
            if (preg_match('/cURL error \d+: (.*) \(see/', $message, $matches)) {
                throw new Exception($matches[1]);
            } else {
                throw new Exception('Connection Error: ' . $message);
            }
        } catch (RequestException $e) {
            throw new Exception($this->handleRequestException($e));
        } catch (GuzzleException $e) {
            throw new Exception('Guzzle Error: ' . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Extracts a meaningful error response from a RequestException.
     *
     * @param RequestException $e
     * @return string
     */
    private function handleRequestException(RequestException $e): string
    {
        if (!$e->hasResponse()) {
            return $e->getMessage();
        }

        $response = $e->getResponse();
        $message = $response->getBody()->getContents();
        $contentType = $response->getHeaderLine('Content-Type');

        if (str_starts_with($contentType, 'text/html') || str_starts_with($contentType, 'text/plain')) {
            $message = strip_tags($message);
        }

        return $message;
    }
}
