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
     * @var string
     */
    protected $defaultPath = "/auth";

    /**
     * @var string
     */
    protected $algo = "sha256";

    /**
     * @param $serverUrl plugNmeet server URL
     * @param $apiKey    plugNmeet API_Key
     * @param $apiSecret plugNmeet API_Secret
     */
    public function __construct($serverUrl, $apiKey, $apiSecret)
    {
        $this->serverUrl = $serverUrl;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
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
     * @param FetchAnalyticsParameters $fetchRecordingsParameters
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
    public function getJWTencodedData(array $payload, int $validity, $algo = "HS256", array $head = []): string
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
    public function decodeJWTData(string $raw, $algo = "HS256"): stdClass
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
     * @param  $path
     * @param array $body
     * @return stdClass
     */
    protected function sendRequest($path, array $body): stdClass
    {
        $output = new stdClass();
        $output->status = false;

        $fields = json_encode($body);
        $signature = hash_hmac($this->algo, $fields, $this->apiSecret);

        $header = array(
            "Content-type: application/json",
            "API-KEY: " . $this->apiKey,
            "HASH-SIGNATURE: " . $signature
        );
        $url = $this->serverUrl . $this->defaultPath . $path;

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cert/cacert.pem');

            $result = curl_exec($ch);
            $error = curl_error($ch);
            $errno = curl_errno($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $response = "";
            if (!empty($result)) {
                $response = json_decode($result);
            }

            if (0 !== $errno) {
                $output->response = "Error: " . $error;
                return $output;
            } elseif ((int)$httpCode !== 200) {
                if (isset($response->msg)) {
                    $output->response = $response->msg;
                } else {
                    $output->response = "HTTP response error code: " . $httpCode;
                }
                return $output;
            }

            $output->status = true;
            $output->response = $response;
        } catch (Exception $e) {
            $output->response = "Exception: " . $e->getMessage();
        }

        return $output;
    }
}
