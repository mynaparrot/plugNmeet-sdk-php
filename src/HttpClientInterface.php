<?php

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
}
