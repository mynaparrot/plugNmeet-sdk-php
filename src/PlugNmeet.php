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
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Mynaparrot\PlugnmeetProto\ArtifactInfoReq;
use Mynaparrot\PlugnmeetProto\ArtifactInfoRes;
use Mynaparrot\PlugnmeetProto\BroadcastToRoomReq;
use Mynaparrot\PlugnmeetProto\CommonResponse;
use Mynaparrot\PlugnmeetProto\CreateRoomReq;
use Mynaparrot\PlugnmeetProto\CreateRoomRes;
use Mynaparrot\PlugnmeetProto\DeleteAnalyticsReq;
use Mynaparrot\PlugnmeetProto\DeleteAnalyticsRes;
use Mynaparrot\PlugnmeetProto\DeleteArtifactReq;
use Mynaparrot\PlugnmeetProto\DeleteArtifactRes;
use Mynaparrot\PlugnmeetProto\DeleteRecordingReq;
use Mynaparrot\PlugnmeetProto\DeleteRecordingRes;
use Mynaparrot\PlugnmeetProto\FetchAnalyticsReq;
use Mynaparrot\PlugnmeetProto\FetchAnalyticsRes;
use Mynaparrot\PlugnmeetProto\FetchArtifactsReq;
use Mynaparrot\PlugnmeetProto\FetchArtifactsRes;
use Mynaparrot\PlugnmeetProto\FetchPastRoomsReq;
use Mynaparrot\PlugnmeetProto\FetchPastRoomsRes;
use Mynaparrot\PlugnmeetProto\FetchRecordingsReq;
use Mynaparrot\PlugnmeetProto\FetchRecordingsRes;
use Mynaparrot\PlugnmeetProto\GenerateTokenReq;
use Mynaparrot\PlugnmeetProto\GenerateTokenRes;
use Mynaparrot\PlugnmeetProto\GetActiveRoomInfoReq;
use Mynaparrot\PlugnmeetProto\GetActiveRoomInfoRes;
use Mynaparrot\PlugnmeetProto\GetActiveRoomsInfoRes;
use Mynaparrot\PlugnmeetProto\GetAnalyticsDownloadTokenReq;
use Mynaparrot\PlugnmeetProto\GetAnalyticsDownloadTokenRes;
use Mynaparrot\PlugnmeetProto\GetArtifactDownloadTokenReq;
use Mynaparrot\PlugnmeetProto\GetArtifactDownloadTokenRes;
use Mynaparrot\PlugnmeetProto\GetClientFilesRes;
use Mynaparrot\PlugnmeetProto\GetDownloadTokenReq;
use Mynaparrot\PlugnmeetProto\GetDownloadTokenRes;
use Mynaparrot\PlugnmeetProto\IsRoomActiveReq;
use Mynaparrot\PlugnmeetProto\IsRoomActiveRes;
use Mynaparrot\PlugnmeetProto\MergeRecordingsReq;
use Mynaparrot\PlugnmeetProto\RecordingInfoReq;
use Mynaparrot\PlugnmeetProto\RecordingInfoRes;
use Mynaparrot\PlugnmeetProto\RoomEndReq;
use Mynaparrot\PlugnmeetProto\RoomEndRes;
use Mynaparrot\PlugnmeetProto\StatusCode;
use Mynaparrot\PlugnmeetProto\UpdateRecordingMetadataReq;
use Mynaparrot\PlugnmeetProto\UpdateRecordingMetadataRes;
use Ramsey\Uuid\Uuid;
use stdClass;

/**
 * The main class for interacting with the plugNmeet API.
 */
class PlugNmeet
{
    /**
     * The URL of the plugNmeet server.
     *
     * @var string
     */
    protected string $serverUrl;

    /**
     * The API key for the plugNmeet server.
     *
     * @var string
     */
    protected string $apiKey;

    /**
     * The API secret for the plugNmeet server.
     *
     * @var string
     */
    protected string $apiSecret;

    /**
     * The HTTP client used to make requests to the plugNmeet server.
     *
     * @var HttpClientInterface
     */
    protected HttpClientInterface $httpClient;

    /**
     * The default path for API requests.
     *
     * @var string
     */
    protected string $defaultPath = "/auth";

    /**
     * The hashing algorithm used to sign requests.
     *
     * @var string
     */
    protected string $algo = "sha256";

    /**
     * Constructs a new PlugNmeet instance.
     *
     * @param string $serverUrl The URL of the plugNmeet server.
     * @param string $apiKey The API key for the plugNmeet server.
     * @param string $apiSecret The API secret for the plugNmeet server.
     * @param int $timeout The timeout for API requests in seconds.
     * @param bool $verifySSL Whether to verify the SSL certificate.
     * @param HttpClientInterface|null $httpClient An optional custom HTTP client.
     */
    public function __construct(
        string $serverUrl,
        string $apiKey,
        string $apiSecret,
        int $timeout = 60,
        bool $verifySSL = true,
        ?HttpClientInterface $httpClient = null
    ) {
        $this->serverUrl = $serverUrl;
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

        if ($httpClient === null) {
            $this->httpClient = new GuzzleHttpClient($timeout, $verifySSL);
        } else {
            $this->httpClient = $httpClient;
        }
    }

    /**
     * Creates a new room.
     *
     * @param CreateRoomReq $createRoomRes The request object for creating a room.
     * @return CreateRoomRes The response from the createRoom API call.
     * @throws Exception
     */
    public function createRoom(CreateRoomReq $createRoomRes): CreateRoomRes
    {
        $body = $createRoomRes->serializeToJsonString();
        $res = $this->sendRequest("/room/create", $body);

        $output = new CreateRoomRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Generates a token for a user to join a room.
     *
     * @param GenerateTokenReq $generateTokenReq The request object for generating a join token.
     * @return GenerateTokenRes The response from the getJoinToken API call.
     * @throws Exception
     */
    public function getJoinToken(GenerateTokenReq $generateTokenReq): GenerateTokenRes
    {
        $body = $generateTokenReq->serializeToJsonString();
        $res = $this->sendRequest("/room/getJoinToken", $body);

        $output = new GenerateTokenRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Checks if a room is currently active.
     *
     * @param IsRoomActiveReq $isRoomActiveReq The request object for checking if a room is active.
     * @return IsRoomActiveRes The response from the isRoomActive API call.
     * @throws Exception
     */
    public function isRoomActive(IsRoomActiveReq $isRoomActiveReq): IsRoomActiveRes
    {
        $body = $isRoomActiveReq->serializeToJsonString();
        $res = $this->sendRequest("/room/isRoomActive", $body);

        $output = new IsRoomActiveRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Retrieves information about an active room.
     *
     * @param GetActiveRoomInfoReq $getActiveRoomInfoReq The request object for getting active room information.
     * @return GetActiveRoomInfoRes The response from the getActiveRoomInfo API call.
     * @throws Exception
     */
    public function getActiveRoomInfo(
        GetActiveRoomInfoReq $getActiveRoomInfoReq
    ): GetActiveRoomInfoRes {
        $body = $getActiveRoomInfoReq->serializeToJsonString();
        $res = $this->sendRequest("/room/getActiveRoomInfo", $body);

        $output = new GetActiveRoomInfoRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Retrieves information about all active rooms.
     *
     * @return GetActiveRoomsInfoRes The response from the getActiveRoomsInfo API call.
     * @throws Exception
     */
    public function getActiveRoomsInfo(): GetActiveRoomsInfoRes
    {
        $res = $this->sendRequest("/room/getActiveRoomsInfo", "{}");
        $output = new GetActiveRoomsInfoRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Retrieves a list of past rooms.
     *
     * @param FetchPastRoomsReq $fetchPastRoomsReq The request object for fetching past rooms.
     * @return FetchPastRoomsRes The response from the fetchPastRoomsInfo API call.
     * @throws Exception
     */
    public function fetchPastRoomsInfo(FetchPastRoomsReq $fetchPastRoomsReq): FetchPastRoomsRes
    {
        $body = $fetchPastRoomsReq->serializeToJsonString();
        $res = $this->sendRequest("/room/fetchPastRooms", $body);

        $output = new FetchPastRoomsRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Broadcast messages or notifications directly into an active Plug-N-Meet session in real-time
     *
     * @param BroadcastToRoomReq $broadcastToRoomReq The request object for broadcasting to room.
     * @return CommonResponse The response from the API call.
     * @throws Exception
     */
    public function broadcastToRoom(BroadcastToRoomReq $broadcastToRoomReq): CommonResponse
    {
        $body = $broadcastToRoomReq->serializeToJsonString();
        $res = $this->sendRequest("/room/broadcastToRoom", $body);

        $output = new CommonResponse();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Upload a file to the whiteboard.
     *
     * @param string $roomId The ID of the room to upload the file to.
     * @param array $options An array containing either a 'document' (local full file path) or 'document_link' (URL).
     * @return CommonResponse The response from the server.
     * @throws Exception
     */
    public function uploadWhiteboardFile(string $roomId, array $options): CommonResponse
    {
        $output = new CommonResponse();
        $output->setStatusCode(StatusCode::INVALID_PARAMETERS);

        if (!isset($options['document']) && !isset($options['document_link'])) {
            $output->setMsg("Either 'document' or 'document_link' must be provided.");
            return $output;
        }

        if (isset($options['document']) && isset($options['document_link'])) {
            $output->setMsg("Only one of 'document' or 'document_link' can be provided.");
            return $output;
        }

        $multipart = [];
        if (isset($options['document'])) {
            if (!is_file($options['document']) || !is_readable($options['document'])) {
                $output->setMsg("The provided 'document' is not a valid or readable file.");
                return $output;
            }
            $multipart[] = [
                'name' => 'document',
                'contents' => fopen($options['document'], 'r'),
            ];
        } else {
            if (filter_var($options['document_link'], FILTER_VALIDATE_URL) === false) {
                $output->setMsg("The provided 'document_link' is not a valid URL.");
                return $output;
            }
            $multipart[] = [
                'name' => 'document_link',
                'contents' => $options['document_link'],
            ];
        }

        $res = $this->sendUploadFileRequest($roomId, $multipart);
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Ends an active session.
     *
     * @param RoomEndReq $roomEndReq The request object for ending a room.
     * @return RoomEndRes The response from the endRoom API call.
     * @throws Exception
     */
    public function endRoom(RoomEndReq $roomEndReq): RoomEndRes
    {
        $body = $roomEndReq->serializeToJsonString();
        $res = $this->sendRequest("/room/endRoom", $body);

        $output = new RoomEndRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Retrieves a list of recordings.
     *
     * @param FetchRecordingsReq $fetchRecordingsReq The request object for fetching recordings.
     * @return FetchRecordingsRes The response from the fetchRecordings API call.
     * @throws Exception
     */
    public function fetchRecordings(FetchRecordingsReq $fetchRecordingsReq): FetchRecordingsRes
    {
        $body = $fetchRecordingsReq->serializeToJsonString();
        $res = $this->sendRequest("/recording/fetch", $body);

        $output = new FetchRecordingsRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Retrieves information about a specific recording.
     *
     * @param RecordingInfoReq $recordingInfoReq The request object for getting recording information.
     * @return RecordingInfoRes The response from the getRecordingInfo API call.
     * @throws Exception
     */
    public function getRecordingInfo(RecordingInfoReq $recordingInfoReq): RecordingInfoRes
    {
        $body = $recordingInfoReq->serializeToJsonString();
        $res = $this->sendRequest("/recording/info", $body);

        $output = new RecordingInfoRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Updates the metadata of a recording.
     *
     * @param UpdateRecordingMetadataReq $updateRecordingMetadataReq The request object for updating recording metadata.
     * @return UpdateRecordingMetadataRes The response from the updateRecordingMetadata API call.
     * @throws Exception
     */
    public function updateRecordingMetadata(
        UpdateRecordingMetadataReq $updateRecordingMetadataReq
    ): UpdateRecordingMetadataRes {
        $body = $updateRecordingMetadataReq->serializeToJsonString();
        $res = $this->sendRequest("/recording/updateMetadata", $body);

        $output = new UpdateRecordingMetadataRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Merge multiple parts of a session's recording into a single new recording.
     *
     * @param MergeRecordingsReq $mergeRecordingsReq The request object for merging recording.
     * @return CommonResponse The response from the API call.
     * @throws Exception
     */
    public function mergeRecordings(
        MergeRecordingsReq $mergeRecordingsReq
    ): CommonResponse {
        $body = $mergeRecordingsReq->serializeToJsonString();
        $res = $this->sendRequest("/recording/mergeRecordings", $body);

        $output = new CommonResponse();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Deletes a recording.
     *
     * @param DeleteRecordingReq $deleteRecordingReq The request object for deleting a recording.
     * @return DeleteRecordingRes The response from the deleteRecordings API call.
     * @throws Exception
     */
    public function deleteRecordings(DeleteRecordingReq $deleteRecordingReq): DeleteRecordingRes
    {
        $body = $deleteRecordingReq->serializeToJsonString();
        $res = $this->sendRequest("/recording/delete", $body);

        $output = new DeleteRecordingRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Generates a download link for a recording.
     *
     * @param GetDownloadTokenReq $getDownloadTokenReq The request object for getting a recording download token.
     * @return GetDownloadTokenRes The response from the getRecordingDownloadToken API call.
     * @throws Exception
     */
    public function getRecordingDownloadToken(
        GetDownloadTokenReq $getDownloadTokenReq
    ): GetDownloadTokenRes {
        $body = $getDownloadTokenReq->serializeToJsonString();
        $res = $this->sendRequest("/recording/getDownloadToken", $body);

        $output = new GetDownloadTokenRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Retrieves a list of artifacts.
     *
     * @param FetchArtifactsReq $fetchArtifactsReq The request object for fetching artifacts.
     * @return FetchArtifactsRes The response from the fetchArtifacts API call.
     * @throws Exception
     */
    public function fetchArtifacts(FetchArtifactsReq $fetchArtifactsReq): FetchArtifactsRes
    {
        $body = $fetchArtifactsReq->serializeToJsonString();
        $res = $this->sendRequest("/artifact/fetch", $body);

        $output = new FetchArtifactsRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Retrieves information about a specific artifact.
     *
     * @param ArtifactInfoReq $artifactDetailsReq The request object for getting artifact information.
     * @return ArtifactInfoRes The response from the getArtifactInfo API call.
     * @throws Exception
     */
    public function getArtifactInfo(ArtifactInfoReq $artifactDetailsReq): ArtifactInfoRes
    {
        $body = $artifactDetailsReq->serializeToJsonString();
        $res = $this->sendRequest("/artifact/info", $body);

        $output = new ArtifactInfoRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Deletes an artifact.
     *
     * @param DeleteArtifactReq $deleteArtifactReq The request object for deleting an artifact.
     * @return DeleteArtifactRes The response from the deleteArtifact API call.
     * @throws Exception
     */
    public function deleteArtifact(DeleteArtifactReq $deleteArtifactReq): DeleteArtifactRes
    {
        $body = $deleteArtifactReq->serializeToJsonString();
        $res = $this->sendRequest("/artifact/delete", $body);

        $output = new DeleteArtifactRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Generates a download token for an artifact.
     *
     * @param GetArtifactDownloadTokenReq $getAnalyticsDownloadTokenReq The request object for getting an artifact download token.
     * @return GetArtifactDownloadTokenRes The response from the getArtifactDownloadToken API call.
     * @throws Exception
     */
    public function getArtifactDownloadToken(
        GetArtifactDownloadTokenReq $getAnalyticsDownloadTokenReq
    ): GetArtifactDownloadTokenRes {
        $body = $getAnalyticsDownloadTokenReq->serializeToJsonString();
        $res = $this->sendRequest("/artifact/getDownloadToken", $body);

        $output = new GetArtifactDownloadTokenRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Retrieves analytics data.
     *
     * @param FetchAnalyticsReq $fetchAnalyticsReq The request object for fetching analytics.
     * @return FetchAnalyticsRes The response from the fetchAnalytics API call.
     * @throws Exception
     * @deprecated This method is deprecated and will be removed in a future version. Use fetchArtifacts instead.
     */
    public function fetchAnalytics(FetchAnalyticsReq $fetchAnalyticsReq): FetchAnalyticsRes
    {
        $body = $fetchAnalyticsReq->serializeToJsonString();
        $res = $this->sendRequest("/analytics/fetch", $body);

        $output = new FetchAnalyticsRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Deletes analytics data.
     *
     * @param DeleteAnalyticsReq $deleteAnalyticsReq The request object for deleting analytics.
     * @return DeleteAnalyticsRes The response from the deleteAnalytics API call.
     * @throws Exception
     * @deprecated This method is deprecated and will be removed in a future version. Use deleteArtifact instead.
     */
    public function deleteAnalytics(DeleteAnalyticsReq $deleteAnalyticsReq): DeleteAnalyticsRes
    {
        $body = $deleteAnalyticsReq->serializeToJsonString();
        $res = $this->sendRequest("/analytics/delete", $body);

        $output = new DeleteAnalyticsRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Generates a download token for analytics data.
     *
     * @param GetAnalyticsDownloadTokenReq $getAnalyticsDownloadTokenReq The request object for getting an analytics download token.
     * @return GetAnalyticsDownloadTokenRes The response from the getAnalyticsDownloadToken API call.
     * @throws Exception
     * @deprecated This method is deprecated and will be removed in a future version. Use getArtifactDownloadToken instead.
     */
    public function getAnalyticsDownloadToken(
        GetAnalyticsDownloadTokenReq $getAnalyticsDownloadTokenReq
    ): GetAnalyticsDownloadTokenRes {
        $body = $getAnalyticsDownloadTokenReq->serializeToJsonString();
        $res = $this->sendRequest("/analytics/getDownloadToken", $body);

        $output = new GetAnalyticsDownloadTokenRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Retrieves the client files needed to build the plugNmeet interface.
     *
     * @return GetClientFilesRes The response from the getClientFiles API call.
     * @throws Exception
     */
    public function getClientFiles(): GetClientFilesRes
    {
        $res = $this->sendRequest("/getClientFiles", "{}");
        $output = new GetClientFilesRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response)->setStatusCode($res->status_code);
        }
        return $output;
    }

    /**
     * Encodes a payload into a JWT.
     *
     * @param array $payload The payload to encode.
     * @param int $validity The validity of the token in seconds.
     * @param string $algo The hashing algorithm to use.
     * @param array $head Optional headers for the JWT.
     * @return string The encoded JWT.
     */
    public function getJWTencodedData(array $payload, int $validity, string $algo = "HS256", array $head = []): string
    {
        $payload['iss'] = $this->apiKey;
        $payload['nbf'] = time();
        $payload['exp'] = time() + $validity;

        return JWT::encode($payload, $this->apiSecret, $algo, null, $head);
    }

    /**
     * Decodes a JWT.
     *
     * @param string $raw The JWT to decode.
     * @param string $algo The hashing algorithm used to encode the JWT.
     * @return stdClass The decoded payload.
     */
    public function decodeJWTData(string $raw, string $algo = "HS256"): stdClass
    {
        return JWT::decode($raw, new Key($this->apiSecret, $algo));
    }

    /**
     * Generates a UUID v4 random string.
     *
     * @return string The generated UUID v4.
     */
    public function getUUID(): string
    {
        $uuid = Uuid::uuid4();
        return $uuid->toString();
    }

    /**
     * Generates a UUID v4 random string.
     *
     * @return string The generated UUID v4.
     */
    public static function generateUuid4(): string
    {
        return Uuid::uuid4()->toString();
    }

    /**
     * Builds the full URL for an API endpoint.
     *
     * @param string $path The API endpoint path.
     * @return string The full URL.
     */
    private function buildUrl(string $path): string
    {
        return implode('/', [
            rtrim($this->serverUrl, '/'),
            trim($this->defaultPath, '/'),
            trim($path, '/')
        ]);
    }

    /**
     * Sends a request to the plugNmeet server.
     *
     * @param string $path The API endpoint to call.
     * @param string $body The body of the request.
     * @return stdClass The response from the server.
     */
    protected function sendRequest(string $path, string $body): stdClass
    {
        $output = new stdClass();
        $output->status = false;
        $output->status_code = StatusCode::UNKNOWN_STATUS;
        $signature = hash_hmac($this->algo, $body, $this->apiSecret);

        try {
            $response = $this->httpClient->post(
                $this->buildUrl($path),
                $body,
                [
                    'Content-Type' => 'application/json',
                    'API-KEY' => $this->apiKey,
                    'HASH-SIGNATURE' => $signature,
                ]
            );

            $output->status = true;
            $output->response = $response;
        } catch (Exception $e) {
            $this->handleException($e->getMessage(), $output);
        }

        return $output;
    }


    /**
     * @param string $roomId The ID of the room to upload the file to.
     * @param array $multipart The multipart data to send.
     * @return stdClass
     */
    protected function sendUploadFileRequest(string $roomId, array $multipart): stdClass
    {
        $output = new stdClass();
        $output->status = false;
        $output->status_code = StatusCode::UNKNOWN_STATUS;
        $signature = hash_hmac($this->algo, $roomId, $this->apiSecret);

        try {
            $response = $this->httpClient->uploadFile(
                $this->buildUrl('room/uploadWhiteboardFile'),
                $multipart,
                [
                    'API-KEY' => $this->apiKey,
                    'HASH-SIGNATURE' => $signature,
                    'Room-Id' => $roomId,
                ]
            );

            $output->status = true;
            $output->response = $response;
        } catch (Exception $e) {
            $this->handleException($e->getMessage(), $output);
        }

        return $output;
    }

    /**
     * Extracts a meaningful error response from a Exception.
     *
     * @param string $message
     * @param $output
     */
    private function handleException(string $message, &$output): void
    {
        try {
            $common = new CommonResponse();
            $common->mergeFromJsonString($message, true);
            $output->status_code = $common->getStatusCode();
            $output->response = $common->getMsg();
        } catch (Exception $e) {
            $output->response = $message;
        }
    }
}
