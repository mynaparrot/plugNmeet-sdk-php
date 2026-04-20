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
use Mynaparrot\PlugnmeetProto\ArtifactInfoReq;
use Mynaparrot\PlugnmeetProto\ArtifactInfoRes;
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
use Mynaparrot\PlugnmeetProto\RecordingInfoReq;
use Mynaparrot\PlugnmeetProto\RecordingInfoRes;
use Mynaparrot\PlugnmeetProto\RoomEndReq;
use Mynaparrot\PlugnmeetProto\RoomEndRes;
use Mynaparrot\PlugnmeetProto\UpdateRecordingMetadataReq;
use Mynaparrot\PlugnmeetProto\UpdateRecordingMetadataRes;
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
    protected string $serverUrl;
    /**
     * @var string
     */
    protected string $apiKey;
    /**
     * @var string
     */
    protected string $apiSecret;

    /**
     * @var HttpClientInterface
     */
    protected HttpClientInterface $httpClient;

    /**
     * @var string
     */
    protected string $defaultPath = "/auth";

    /**
     * @var string
     */
    protected string $algo = "sha256";

    /**
     * @param string $serverUrl plugNmeet server URL
     * @param string $apiKey plugNmeet API_Key
     * @param string $apiSecret plugNmeet API_Secret
     * @param int $timeout
     * @param bool $verifySSL
     * @param HttpClientInterface|null $httpClient
     */
    public function __construct(
        string $serverUrl,
        string $apiKey,
        string $apiSecret,
        int $timeout = 60,
        bool $verifySSL = true,
        ?HttpClientInterface $httpClient = null
    ) {
        $this->serverUrl = rtrim($serverUrl, "/");
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;

        if ($httpClient === null) {
            $this->httpClient = new GuzzleHttpClient($timeout, $verifySSL);
        } else {
            $this->httpClient = $httpClient;
        }
    }

    /**
     * Create new room
     *
     * @param CreateRoomReq $createRoomRes
     * @return CreateRoomRes
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * Generate join token
     *
     * @param GenerateTokenReq $generateTokenReq
     * @return GenerateTokenRes
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * To check if room is active or not
     *
     * @param IsRoomActiveReq $isRoomActiveReq
     * @return IsRoomActiveRes
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * Get active room information
     *
     * @param GetActiveRoomInfoReq $getActiveRoomInfoReq
     * @return GetActiveRoomInfoRes
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * Get all active rooms
     *
     * @return GetActiveRoomsInfoRes
     * @throws Exception
     */
    public function getActiveRoomsInfo(): GetActiveRoomsInfoRes
    {
        $res = $this->sendRequest("/room/getActiveRoomsInfo", "{}");
        $output = new GetActiveRoomsInfoRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * Get all past rooms
     * @param FetchPastRoomsReq $fetchPastRoomsReq
     * @return FetchPastRoomsRes
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * End active room
     *
     * @param RoomEndReq $roomEndReq
     * @return RoomEndRes
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * To fetch recordings
     *
     * @param FetchRecordingsReq $fetchRecordingsReq
     * @return FetchRecordingsRes
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * To get recording info
     *
     * @param RecordingInfoReq $recordingInfoReq
     * @return RecordingInfoRes
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * To update recording metadata
     *
     * @param UpdateRecordingMetadataReq $updateRecordingMetadataReq
     * @return UpdateRecordingMetadataRes
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * To delete recording
     *
     * @param DeleteRecordingReq $deleteRecordingReq
     * @return DeleteRecordingRes
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * Generate token to download recording
     *
     * @param GetDownloadTokenReq $getDownloadTokenReq
     * @return GetDownloadTokenRes
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * To fetch artifacts list
     *
     * @param FetchArtifactsReq $fetchArtifactsReq
     * @return FetchArtifactsRes
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * To get details of an artifact
     *
     * @param ArtifactInfoReq $artifactDetailsReq
     * @return ArtifactInfoRes
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * To delete artifact
     *
     * @param DeleteArtifactReq $deleteArtifactReq
     * @return DeleteArtifactRes
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * Generate token to download artifact file
     *
     * @param GetArtifactDownloadTokenReq $getAnalyticsDownloadTokenReq
     * @return GetArtifactDownloadTokenRes
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * Deprecated: To fetch analytics
     *
     * @param FetchAnalyticsReq $fetchAnalyticsReq
     * @return FetchAnalyticsRes
     * @throws Exception
     * @deprecated replaced by artifact
     */
    public function fetchAnalytics(FetchAnalyticsReq $fetchAnalyticsReq): FetchAnalyticsRes
    {
        $body = $fetchAnalyticsReq->serializeToJsonString();
        $res = $this->sendRequest("/analytics/fetch", $body);

        $output = new FetchAnalyticsRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * Deprecated: To delete analytics
     *
     * @param DeleteAnalyticsReq $deleteAnalyticsReq
     * @return DeleteAnalyticsRes
     * @throws Exception
     * @deprecated replaced by artifact
     */
    public function deleteAnalytics(DeleteAnalyticsReq $deleteAnalyticsReq): DeleteAnalyticsRes
    {
        $body = $deleteAnalyticsReq->serializeToJsonString();
        $res = $this->sendRequest("/analytics/delete", $body);

        $output = new DeleteAnalyticsRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * Deprecated: Generate token to download analytics
     *
     * @param GetAnalyticsDownloadTokenReq $getAnalyticsDownloadTokenReq
     * @return GetAnalyticsDownloadTokenRes
     * @throws Exception
     * @deprecated replaced by artifact
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
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
    }

    /**
     * @return GetClientFilesRes
     * @throws Exception
     */
    public function getClientFiles(): GetClientFilesRes
    {
        $res = $this->sendRequest("/getClientFiles", "{}");
        $output = new GetClientFilesRes();
        if ($res->status) {
            $output->mergeFromJsonString($res->response, true);
        } else {
            $output->setStatus(false)->setMsg($res->response);
        }
        return $output;
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
     * @param string $body
     * @return stdClass
     */
    protected function sendRequest($path, string $body): stdClass
    {
        $output = new stdClass();
        $output->status = false;
        $signature = hash_hmac($this->algo, $body, $this->apiSecret);

        try {
            $url = $this->serverUrl . $this->defaultPath . $path;
            $response = $this->httpClient->post(
                $url,
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
            $output->response = $e->getMessage();
        }

        return $output;
    }
}
