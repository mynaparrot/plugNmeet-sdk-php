<?php

namespace Mynaparrot\Plugnmeet;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

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
     * Extracts a meaningful error response from a RequestException.
     *
     * @param RequestException $e
     * @return mixed|string
     */
    private function handleRequestException(RequestException $e): mixed
    {
        if (!$e->hasResponse()) {
            return $e->getMessage();
        }

        $responseBody = $e->getResponse()->getBody()->getContents();
        $decodedResponse = json_decode($responseBody);

        // Return the decoded JSON if successful, otherwise fall back to the raw body.
        return json_last_error() === JSON_ERROR_NONE
            ? json_encode($decodedResponse) // Re-encode to string for consistency
            : $responseBody;
    }
}
