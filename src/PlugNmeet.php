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

namespace Mynaparrot\Plugnmeet;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use GuzzleHttp\Client;
use Mynaparrot\Plugnmeet\Parameters\AnalyticsDownloadTokenParameters;
use Mynaparrot\Plugnmeet\Parameters\CreateRoomParameters;
use Mynaparrot\Plugnmeet\Parameters\DeleteAnalyticsParameters;
use Mynaparrot\Plugnmeet\Parameters\DeleteRecordingParameters;
use Mynaparrot\Plugnmeet\Parameters\EndRoomParameters;
use Mynaparrot\Plugnmeet\Parameters\FetchAnalyticsParameters;
use Mynaparrot\Plugnmeet\Parameters\FetchPastRoomsParameters;
use Mynaparrot\Plugnmeet\Parameters\FetchRecordingsParameters;
use Mynaparrot\Plugnmeet\Parameters\GenerateJoinTokenParameters;
use Mynaparrot\Plugnmeet\Parameters\GetActiveRoomInfoParameters;
use Mynaparrot\Plugnmeet\Parameters\IsRoomActiveParameters;
use Mynaparrot\Plugnmeet\Parameters\RecordingDownloadTokenParameters;
use Mynaparrot\Plugnmeet\Parameters\RecordingInfoParameters;
use Mynaparrot\Plugnmeet\Responses\AnalyticsDownloadTokenResponse;
use Mynaparrot\Plugnmeet\Responses\ClientFilesResponses;
use Mynaparrot\Plugnmeet\Responses\CreateRoomResponse;
use Mynaparrot\Plugnmeet\Responses\DeleteAnalyticsResponse;
use Mynaparrot\Plugnmeet\Responses\DeleteRecordingResponse;
use Mynaparrot\Plugnmeet\Responses\EndRoomResponse;
use Mynaparrot\Plugnmeet\Responses\FetchAnalyticsResponse;
use Mynaparrot\Plugnmeet\Responses\FetchPastRoomsResponse;
use Mynaparrot\Plugnmeet\Responses\FetchRecordingsResponse;
use Mynaparrot\Plugnmeet\Responses\GenerateJoinTokenResponse;
use Mynaparrot\Plugnmeet\Responses\GetActiveRoomInfoResponse;
use Mynaparrot\Plugnmeet\Responses\GetActiveRoomsInfoResponse;
use Mynaparrot\Plugnmeet\Responses\IsRoomActiveResponse;
use Mynaparrot\Plugnmeet\Responses\RecordingDownloadTokenResponse;
use Mynaparrot\Plugnmeet\Responses\RecordingInfoResponse;
use Ramsey\Uuid\Uuid;
use stdClass;

/**
 *
 */
class PlugNmeet
{
    /**
     * @var string
     */
    protected $serverUrl;
    /**
     * @var string
     */
    protected $apiKey;
    /**
     * @var string
     */
    protected $apiSecret;

    /**
     * @var Client
     */
    protected $guzzleClient;

    /**
     * @var string
     */
    protected $defaultPath = "/auth";

    /**
     * @var string
     */
    protected $algo = "sha256";

    /**
     * @param string $serverUrl plugNmeet server URL
     * @param string $apiKey plugNmeet API_Key
     * @param string $apiSecret plugNmeet API_Secret
     */
    public function __construct(string $serverUrl, string $apiKey, string $apiSecret, int $timeout = 60, bool $verifySSL = true)
    {
        $this->serverUrl = rtrim($serverUrl, "/");
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

        $this->guzzleClient = new Client([
                                             'base_uri' => $this->serverUrl,
                                             'timeout' => $timeout,
                                             'verify' => $verifySSL,
                                         ]);
    }

    /**
     * Create new room
     *
     * @param CreateRoomParameters $createRoomParameters
     * @return CreateRoomResponse
     */
    public function createRoom(CreateRoomParameters $createRoomParameters): CreateRoomResponse
    {
        $body = $createRoomParameters->buildBody();
        $output = $this->sendRequest("/room/create", $body);
        return new CreateRoomResponse($output);
    }

    /**
     * Generate join token
     *
     * @param GenerateJoinTokenParameters $generateJoinTokenParameters
     * @return GenerateJoinTokenResponse
     */
    public function getJoinToken(GenerateJoinTokenParameters $generateJoinTokenParameters): GenerateJoinTokenResponse
    {
        $body = $generateJoinTokenParameters->buildBody();
        $output = $this->sendRequest("/room/getJoinToken", $body);
        return new GenerateJoinTokenResponse($output);
    }

    /**
     * To check if room is active or not
     *
     * @param IsRoomActiveParameters $isRoomActiveParameters
     * @return IsRoomActiveResponse
     */
    public function isRoomActive(IsRoomActiveParameters $isRoomActiveParameters): IsRoomActiveResponse
    {
        $body = $isRoomActiveParameters->buildBody();
        $output = $this->sendRequest("/room/isRoomActive", $body);
        return new IsRoomActiveResponse($output);
    }

    /**
     * Get active room information
     *
     * @param GetActiveRoomInfoParameters $getActiveRoomInfoParameters
     * @return GetActiveRoomInfoResponse
     */
    public function getActiveRoomInfo(
        GetActiveRoomInfoParameters $getActiveRoomInfoParameters
    ): GetActiveRoomInfoResponse {
        $body = $getActiveRoomInfoParameters->buildBody();
        $output = $this->sendRequest("/room/getActiveRoomInfo", $body);
        return new GetActiveRoomInfoResponse($output);
    }

    /**
     * Get all active rooms
     *
     * @return GetActiveRoomsInfoResponse
     */
    public function getActiveRoomsInfo(): GetActiveRoomsInfoResponse
    {
        $output = $this->sendRequest("/room/getActiveRoomsInfo", []);
        return new GetActiveRoomsInfoResponse($output);
    }

    /**
     * Get all past rooms
     * @param FetchPastRoomsParameters $fetchPastRoomsParameters
     * @return FetchPastRoomsResponse
     */
    public function fetchPastRoomsInfo(FetchPastRoomsParameters $fetchPastRoomsParameters): FetchPastRoomsResponse
    {
        $body = $fetchPastRoomsParameters->buildBody();
        $output = $this->sendRequest("/room/fetchPastRooms", $body);
        return new FetchPastRoomsResponse($output);
    }

    /**
     * End active room
     *
     * @param EndRoomParameters $endRoomParameters
     * @return EndRoomResponse
     */
    public function endRoom(EndRoomParameters $endRoomParameters): EndRoomResponse
    {
        $body = $endRoomParameters->buildBody();
        $output = $this->sendRequest("/room/endRoom", $body);
        return new EndRoomResponse($output);
    }

    /**
     * To fetch recordings
     *
     * @param FetchRecordingsParameters $fetchRecordingsParameters
     * @return FetchRecordingsResponse
     */
    public function fetchRecordings(FetchRecordingsParameters $fetchRecordingsParameters): FetchRecordingsResponse
    {
        $body = $fetchRecordingsParameters->buildBody();
        $output = $this->sendRequest("/recording/fetch", $body);
        return new FetchRecordingsResponse($output);
    }

    /**
     * To get recording info
     *
     * @param RecordingInfoParameters $recordingInfoParameters
     * @return RecordingInfoResponse
     */
    public function getRecordingInfo(RecordingInfoParameters $recordingInfoParameters): RecordingInfoResponse
    {
        $body = $recordingInfoParameters->buildBody();
        $output = $this->sendRequest("/recording/recordingInfo", $body);
        return new RecordingInfoResponse($output);
    }

    /**
     * To delete recording
     *
     * @param DeleteRecordingParameters $deleteRecordingParameters
     * @return DeleteRecordingResponse
     */
    public function deleteRecordings(DeleteRecordingParameters $deleteRecordingParameters): DeleteRecordingResponse
    {
        $body = $deleteRecordingParameters->buildBody();
        $output = $this->sendRequest("/recording/delete", $body);
        return new DeleteRecordingResponse($output);
    }

    /**
     * Generate token to download recording
     *
     * @param RecordingDownloadTokenParameters $recordingDownloadTokenParameters
     * @return RecordingDownloadTokenResponse
     */
    public function getRecordingDownloadToken(
        RecordingDownloadTokenParameters $recordingDownloadTokenParameters
    ): RecordingDownloadTokenResponse {
        $body = $recordingDownloadTokenParameters->buildBody();
        $output = $this->sendRequest("/recording/getDownloadToken", $body);
        return new RecordingDownloadTokenResponse($output);
    }

    /**
     * To fetch analytics
     *
     * @param FetchAnalyticsParameters $fetchAnalyticsParameters
     * @return FetchAnalyticsResponse
     */
    public function fetchAnalytics(FetchAnalyticsParameters $fetchAnalyticsParameters): FetchAnalyticsResponse
    {
        $body = $fetchAnalyticsParameters->buildBody();
        $output = $this->sendRequest("/analytics/fetch", $body);
        return new FetchAnalyticsResponse($output);
    }

    /**
     * To delete analytics
     *
     * @param DeleteAnalyticsParameters $deleteAnalyticsParameters
     * @return DeleteAnalyticsResponse
     */
    public function deleteAnalytics(DeleteAnalyticsParameters $deleteAnalyticsParameters): DeleteAnalyticsResponse
    {
        $body = $deleteAnalyticsParameters->buildBody();
        $output = $this->sendRequest("/analytics/delete", $body);
        return new DeleteAnalyticsResponse($output);
    }

    /**
     * Generate token to download analytics
     *
     * @param AnalyticsDownloadTokenParameters $analyticsDownloadTokenParameters
     * @return AnalyticsDownloadTokenResponse
     */
    public function getAnalyticsDownloadToken(
        AnalyticsDownloadTokenParameters $analyticsDownloadTokenParameters
    ): AnalyticsDownloadTokenResponse {
        $body = $analyticsDownloadTokenParameters->buildBody();
        $output = $this->sendRequest("/analytics/getDownloadToken", $body);
        return new AnalyticsDownloadTokenResponse($output);
    }

    /**
     * @return ClientFilesResponses
     */
    public function getClientFiles(): ClientFilesResponses
    {
        $output = $this->sendRequest("/getClientFiles", []);
        return new ClientFilesResponses($output);
    }

    /**
     * @param array $payload
     * @param int $validity in seconds
     * @param string $algo
     * @param array $head
     * @return string
     */
    public function getJWTencodedData(array $payload, int $validity, string $algo = "HS256", array $head = []): string
    {
        $payload['iss'] = $this->apiKey;
        $payload['nbf'] = time();
        $payload['exp'] = time() + $validity;

        return JWT::encode($payload, $this->apiSecret, $algo, null, $head);
    }

    /**
     * @param string $raw
     * @param string $algo
     * @return stdClass
     */
    public function decodeJWTData(string $raw, string $algo = "HS256"): stdClass
    {
        return JWT::decode($raw, new Key($this->apiSecret, $algo));
    }

    /**
     * Generate UUID random string
     *
     * @return string
     */
    public function getUUID(): string
    {
        $uuid = Uuid::uuid4();
        return $uuid->toString();
    }

    /**
     * Generate UUID v4 random string
     *
     * @return string
     */
    public static function generateUuid4(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * @param $path
     * @param array $body
     * @return stdClass
     */
    protected function sendRequest($path, array $body): stdClass
    {
        $output = new stdClass();
        $output->status = false;

        $fields = json_encode($body);
        $signature = hash_hmac($this->algo, $fields, $this->apiSecret);

        try {
            $response = $this->guzzleClient->post($this->defaultPath . $path, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'API-KEY' => $this->apiKey,
                    'HASH-SIGNATURE' => $signature,
                ],
                'body' => $fields,
            ]);

            $output->status = true;
            $output->response = json_decode($response->getBody()->getContents());
        } catch (Exception $e) {
            $output->response = $e->getMessage();
        }

        return $output;
    }
}
