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
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

use Google\Protobuf\Internal\MapField;
use Mynaparrot\Plugnmeet\AnalyticsFormatter;
use Mynaparrot\Plugnmeet\HttpClientInterface;
use Mynaparrot\Plugnmeet\PlugNmeet;
use Mynaparrot\Plugnmeet\RoomCreateFeaturesBuilder;
use Mynaparrot\PlugnmeetProto\ArtifactInfoReq;
use Mynaparrot\PlugnmeetProto\ArtifactInfoRes;
use Mynaparrot\PlugnmeetProto\BroadcastToRoomReq;
use Mynaparrot\PlugnmeetProto\CommonResponse;
use Mynaparrot\PlugnmeetProto\CopyrightConf;
use Mynaparrot\PlugnmeetProto\CreateRoomReq;
use Mynaparrot\PlugnmeetProto\CreateRoomRes;
use Mynaparrot\PlugnmeetProto\DeleteArtifactReq;
use Mynaparrot\PlugnmeetProto\DeleteArtifactRes;
use Mynaparrot\PlugnmeetProto\DeleteRecordingReq;
use Mynaparrot\PlugnmeetProto\DeleteRecordingRes;
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
use Mynaparrot\PlugnmeetProto\GetArtifactDownloadTokenReq;
use Mynaparrot\PlugnmeetProto\GetArtifactDownloadTokenRes;
use Mynaparrot\PlugnmeetProto\GetClientFilesRes;
use Mynaparrot\PlugnmeetProto\GetDownloadTokenReq;
use Mynaparrot\PlugnmeetProto\GetDownloadTokenRes;
use Mynaparrot\PlugnmeetProto\IsRoomActiveReq;
use Mynaparrot\PlugnmeetProto\IsRoomActiveRes;
use Mynaparrot\PlugnmeetProto\LockSettings;
use Mynaparrot\PlugnmeetProto\RecordingInfoReq;
use Mynaparrot\PlugnmeetProto\RecordingInfoRes;
use Mynaparrot\PlugnmeetProto\RecordingMetadata;
use Mynaparrot\PlugnmeetProto\RoomArtifactType;
use Mynaparrot\PlugnmeetProto\RoomEndReq;
use Mynaparrot\PlugnmeetProto\RoomEndRes;
use Mynaparrot\PlugnmeetProto\RoomMetadata;
use Mynaparrot\PlugnmeetProto\UpdateRecordingMetadataReq;
use Mynaparrot\PlugnmeetProto\UpdateRecordingMetadataRes;
use Mynaparrot\PlugnmeetProto\UserInfo;
use Mynaparrot\PlugnmeetProto\UserMetadata;

require "../vendor/autoload.php";

/**
 * A simple plugNmeet-PHP-SDK wrapper for easy integration.
 * To learn more about the main SDK, visit: https://github.com/mynaparrot/plugNmeet-php-sdk
 */
class plugNmeetConnect
{
    /**
     * The PlugNmeet instance.
     *
     * @var PlugNmeet
     */
    protected PlugNmeet $plugnmeet;

    /**
     * Constructs a new plugNmeetConnect instance.
     *
     * @param stdClass $config The configuration object.
     * @param int $timeout The timeout for API requests in seconds.
     * @param bool $verifySSL Whether to verify the SSL certificate.
     * @param HttpClientInterface|null $httpClient An optional custom HTTP client.
     */
    public function __construct(stdClass $config, int $timeout = 60, bool $verifySSL = true, ?HttpClientInterface $httpClient = null)
    {
        $this->plugnmeet = new PlugNmeet(
            $config->plugnmeet_server_url,
            $config->plugnmeet_api_key,
            $config->plugnmeet_secret,
            $timeout,
            $verifySSL,
            $httpClient
        );
    }

    /**
     * Returns the underlying PlugNmeet instance.
     *
     * @return PlugNmeet The PlugNmeet instance.
     */
    public function getPlugnmeet(): PlugNmeet
    {
        return $this->plugnmeet;
    }

    /**
     * Returns the UUID of the PlugNmeet instance.
     *
     * @return string The UUID.
     */
    public function getUUID(): string
    {
        return $this->plugnmeet->getUUID();
    }

    /**
     * Generates a UUID v4 random string.
     *
     * @return string The generated UUID v4.
     */
    public static function generateUuid4(): string
    {
        return PlugNmeet::generateUuid4();
    }

    /**
     * Creates a new room.
     *
     * @param string $roomId The ID of the room to create.
     * @param string $roomTitle The title of the room.
     * @param array $roomMetadata The metadata for the room.
     * @param string $welcomeMessage An optional welcome message.
     * @param string $logoutUrl An optional logout URL.
     * @param string $webHookUrl An optional webhook URL.
     * @param int $max_participants The maximum number of participants allowed in the room.
     * @param int $empty_timeout The timeout in seconds before an empty room is automatically closed.
     * @param array|MapField $extraData Optional extra data for the room.
     * @return CreateRoomRes The response from the createRoom API call.
     * @throws Exception If the room_features metadata is missing or not an array.
     */
    public function createRoom(string $roomId, string $roomTitle, array $roomMetadata, string $welcomeMessage = "", string $logoutUrl = "", string $webHookUrl = "", int $max_participants = 0, int $empty_timeout = 0, array|MapField $extraData = array()): CreateRoomRes
    {
        if (!isset($roomMetadata['room_features']) || !is_array($roomMetadata['room_features'])) {
            throw new Exception("room_features required and should be an array");
        }

        $featuresBuilder = new RoomCreateFeaturesBuilder($roomMetadata);
        $features = $featuresBuilder->build();

        $metadata = new RoomMetadata();
        $metadata->setRoomFeatures($features);
        $metadata->setRoomTitle($roomTitle);

        if (!empty($welcomeMessage)) {
            $metadata->setWelcomeMessage($welcomeMessage);
        }
        if (!empty($webHookUrl)) {
            $metadata->setWebhookUrl($webHookUrl);
        }
        if (!empty($logoutUrl)) {
            $metadata->setLogoutUrl($logoutUrl);
        }
        if (!empty($extraData)) {
            $metadata->setExtraData($extraData);
        }

        if (isset($roomMetadata['default_lock_settings'])) {
            $lockSettings = new LockSettings($roomMetadata['default_lock_settings']);
            $metadata->setDefaultLockSettings($lockSettings);
        }

        if (isset($roomMetadata['copyright_conf'])) {
            $copyrightConf = new CopyrightConf($roomMetadata['copyright_conf']);
            $metadata->setCopyrightConf($copyrightConf);
        }


        $roomCreateReq = new CreateRoomReq();
        $roomCreateReq->setRoomId($roomId);
        $roomCreateReq->setMetadata($metadata);

        if ($max_participants > 0) {
            $roomCreateReq->setMaxParticipants($max_participants);
        }
        if ($empty_timeout > 0) {
            $roomCreateReq->setEmptyTimeout($empty_timeout);
        }

        return $this->plugnmeet->createRoom($roomCreateReq);
    }

    /**
     * Generates a token for a user to join a room.
     *
     * @param string $roomId The ID of the room to join.
     * @param string $name The name of the user.
     * @param string $userId The ID of the user.
     * @param bool $isAdmin Whether the user is an administrator.
     * @param bool $isHidden Whether the user is hidden.
     * @param UserMetadata|null $userMetadata Optional metadata for the user.
     * @param LockSettings|null $lockSettings Optional lock settings for the user.
     * @return GenerateTokenRes The response from the getJoinToken API call.
     * @throws Exception
     */
    public function getJoinToken(string $roomId, string $name, string $userId, bool $isAdmin, bool $isHidden = false, UserMetadata|null $userMetadata = null, LockSettings|null $lockSettings = null): GenerateTokenRes
    {
        $userInfo = new UserInfo();
        $userInfo->setUserId($userId);
        $userInfo->setName($name);
        $userInfo->setIsAdmin($isAdmin);
        $userInfo->setIsHidden($isHidden);

        if (!is_null($userMetadata)) {
            $userInfo->setUserMetadata($userMetadata);
        }
        if (!is_null($lockSettings)) {
            if (is_null($userMetadata)) {
                $userInfo->setUserMetadata(new UserMetadata());
            }
            $userInfo->getUserMetadata()->setLockSettings($lockSettings);
        }

        $generateTokenReq = new GenerateTokenReq();
        $generateTokenReq->setRoomId($roomId);
        $generateTokenReq->setUserInfo($userInfo);

        return $this->plugnmeet->getJoinToken($generateTokenReq);
    }

    /**
     * Checks if a room is currently active.
     *
     * @param string $roomId The ID of the room to check.
     * @return IsRoomActiveRes The response from the isRoomActive API call.
     * @throws Exception
     */
    public function isRoomActive(string $roomId): IsRoomActiveRes
    {
        $isRoomActiveReq = new IsRoomActiveReq();
        $isRoomActiveReq->setRoomId($roomId);

        return $this->plugnmeet->isRoomActive($isRoomActiveReq);
    }

    /**
     * Retrieves information about an active room.
     *
     * @param string $roomId The ID of the room to get information about.
     * @return GetActiveRoomInfoRes The response from the getActiveRoomInfo API call.
     * @throws Exception
     */
    public function getActiveRoomInfo(string $roomId): GetActiveRoomInfoRes
    {
        $getActiveRoomInfoReq = new GetActiveRoomInfoReq();
        $getActiveRoomInfoReq->setRoomId($roomId);

        return $this->plugnmeet->getActiveRoomInfo($getActiveRoomInfoReq);
    }

    /**
     * Retrieves information about all active rooms.
     *
     * @return GetActiveRoomsInfoRes The response from the getActiveRoomsInfo API call.
     * @throws Exception
     */
    public function getActiveRoomsInfo(): GetActiveRoomsInfoRes
    {
        return $this->plugnmeet->getActiveRoomsInfo();
    }

    /**
     * Ends a room session.
     *
     * @param string $roomId The ID of the room to end.
     * @return RoomEndRes The response from the endRoom API call.
     * @throws Exception
     */
    public function endRoom(string $roomId): RoomEndRes
    {
        $roomEndReq = new RoomEndReq();
        $roomEndReq->setRoomId($roomId);

        return $this->plugnmeet->endRoom($roomEndReq);
    }

    /**
     * Retrieves a list of past rooms.
     *
     * @param array $roomIds An array of room IDs to filter by.
     * @param int $from The starting index for pagination.
     * @param int $limit The maximum number of rooms to return.
     * @param string $orderBy The order in which to sort the rooms (e.g., "DESC").
     * @return FetchPastRoomsRes The response from the fetchPastRoomsInfo API call.
     * @throws Exception
     */
    public function getPastRooms(array $roomIds, int $from = 0, int $limit = 20, string $orderBy = "DESC"): FetchPastRoomsRes
    {
        $fetchPastRoomsReq = new FetchPastRoomsReq();
        $fetchPastRoomsReq->setRoomIds($roomIds);
        $fetchPastRoomsReq->setFrom($from);
        $fetchPastRoomsReq->setLimit($limit);
        $fetchPastRoomsReq->setOrderBy($orderBy);

        return $this->plugnmeet->fetchPastRoomsInfo($fetchPastRoomsReq);
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
        return $this->plugnmeet->broadcastToRoom($broadcastToRoomReq);
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
        return $this->plugnmeet->uploadWhiteboardFile($roomId, $options);
    }

    /**
     * Retrieves a list of recordings.
     *
     * @param array $roomIds An array of room IDs to filter by.
     * @param string|null $roomSid An optional room SID to filter by.
     * @param int $from The starting index for pagination.
     * @param int $limit The maximum number of recordings to return.
     * @param string $orderBy The order in which to sort the recordings (e.g., "DESC").
     * @return FetchRecordingsRes The response from the fetchRecordings API call.
     * @throws Exception
     */
    public function getRecordings(array $roomIds, string|null $roomSid = null, int $from = 0, int $limit = 20, string $orderBy = "DESC"): FetchRecordingsRes
    {
        $fetchRecordingsReq = new FetchRecordingsReq();
        $fetchRecordingsReq->setRoomIds($roomIds);
        if (!is_null($roomSid)) {
            $fetchRecordingsReq->setRoomSid($roomSid);
        }
        $fetchRecordingsReq->setFrom($from);
        $fetchRecordingsReq->setLimit($limit);
        $fetchRecordingsReq->setOrderBy($orderBy);

        return $this->plugnmeet->fetchRecordings($fetchRecordingsReq);
    }

    /**
     * Retrieves information about a specific recording.
     *
     * @param string $recordingId The ID of the recording to get information about.
     * @return RecordingInfoRes The response from the getRecordingInfo API call.
     * @throws Exception
     */
    public function getRecordingInfo(string $recordingId): RecordingInfoRes
    {
        $recordingInfoReq = new RecordingInfoReq();
        $recordingInfoReq->setRecordId($recordingId);

        return $this->plugnmeet->getRecordingInfo($recordingInfoReq);
    }

    /**
     * Updates the metadata of a recording.
     *
     * @param string $recordingId The ID of the recording to update.
     * @param string $title The new title for the recording.
     * @param string|null $description An optional new description for the recording.
     * @param array|MapField $extraData Optional extra data for the recording.
     * @return UpdateRecordingMetadataRes The response from the updateRecordingMetadata API call.
     * @throws Exception
     */
    public function updateRecordingMetadata(string $recordingId, string $title, string|null $description, array|MapField $extraData): UpdateRecordingMetadataRes
    {
        $updateRecordingMetadataReq = new UpdateRecordingMetadataReq();
        $updateRecordingMetadataReq->setRecordId($recordingId);

        $metadata = new RecordingMetadata();
        $metadata->setTitle($title);
        $metadata->setDescription($description);
        $metadata->setExtraData($extraData);
        $updateRecordingMetadataReq->setMetadata($metadata);

        return $this->plugnmeet->updateRecordingMetadata($updateRecordingMetadataReq);
    }

    /**
     * Generates a download link for a recording.
     *
     * @param string $recordingId The ID of the recording to generate a download link for.
     * @return GetDownloadTokenRes The response from the getRecordingDownloadToken API call.
     * @throws Exception
     */
    public function getRecordingDownloadLink(string $recordingId): GetDownloadTokenRes
    {
        $getDownloadTokenReq = new GetDownloadTokenReq();
        $getDownloadTokenReq->setRecordId($recordingId);

        return $this->plugnmeet->getRecordingDownloadToken($getDownloadTokenReq);
    }

    /**
     * Deletes a recording.
     *
     * @param string $recordingId The ID of the recording to delete.
     * @return DeleteRecordingRes The response from the deleteRecordings API call.
     * @throws Exception
     */
    public function deleteRecording(string $recordingId): DeleteRecordingRes
    {
        $deleteRecordingReq = new DeleteRecordingReq();
        $deleteRecordingReq->setRecordId($recordingId);

        return $this->plugnmeet->deleteRecordings($deleteRecordingReq);
    }

    /**
     * Retrieves a list of artifacts.
     *
     * @param array $roomIds An array of room IDs to filter by.
     * @param string|null $roomSid An optional room SID to filter by.
     * @param int|null $artifactsType An optional artifact type to filter by.
     * @param int $from The starting index for pagination.
     * @param int $limit The maximum number of artifacts to return.
     * @param string $orderBy The order in which to sort the artifacts (e.g., "DESC").
     * @return FetchArtifactsRes The response from the fetchArtifacts API call.
     * @throws Exception
     */
    public function getArtifacts(array $roomIds, string|null $roomSid = null, int|null $artifactsType = null, int $from = 0, int $limit = 20, string $orderBy = "DESC"): FetchArtifactsRes
    {
        $fetchArtifactsReq = new FetchArtifactsReq();
        $fetchArtifactsReq->setRoomIds($roomIds);
        if (!is_null($roomSid)) {
            $fetchArtifactsReq->setRoomSid($roomSid);
        }
        if (!is_null($artifactsType)) {
            $fetchArtifactsReq->setType($artifactsType);
        }
        $fetchArtifactsReq->setFrom($from);
        $fetchArtifactsReq->setLimit($limit);
        $fetchArtifactsReq->setOrderBy($orderBy);

        return $this->plugnmeet->fetchArtifacts($fetchArtifactsReq);
    }

    /**
     * Retrieves information about a specific artifact.
     *
     * @param string $artifactId The ID of the artifact to get information about.
     * @return ArtifactInfoRes The response from the getArtifactInfo API call.
     * @throws Exception
     */
    public function getArtifactInfo(string $artifactId): ArtifactInfoRes
    {
        $artifactInfoReq = new ArtifactInfoReq();
        $artifactInfoReq->setArtifactId($artifactId);

        return $this->plugnmeet->getArtifactInfo($artifactInfoReq);
    }

    /**
     * Generates a download token for an artifact.
     *
     * @param string $artifactId The ID of the artifact to generate a download token for.
     * @return GetArtifactDownloadTokenRes The response from the getArtifactDownloadToken API call.
     * @throws Exception
     */
    public function getArtifactDownloadToken(string $artifactId): GetArtifactDownloadTokenRes
    {
        $getDownloadTokenReq = new GetArtifactDownloadTokenReq();
        $getDownloadTokenReq->setArtifactId($artifactId);

        return $this->plugnmeet->getArtifactDownloadToken($getDownloadTokenReq);
    }

    /**
     * Deletes an artifact.
     *
     * @param string $artifactId The ID of the artifact to delete.
     * @return DeleteArtifactRes The response from the deleteArtifact API call.
     * @throws Exception
     */
    public function deleteArtifact(string $artifactId): DeleteArtifactRes
    {
        $deleteArtifactReq = new DeleteArtifactReq();
        $deleteArtifactReq->setArtifactId($artifactId);

        return $this->plugnmeet->deleteArtifact($deleteArtifactReq);
    }

    /**
     * Retrieves analytics data.
     *
     * @param array $roomIds An array of room IDs to filter by.
     * @param string|null $roomSid An optional room SID to filter by.
     * @param int $from The starting index for pagination.
     * @param int $limit The maximum number of analytics to return.
     * @param string $orderBy The order in which to sort the analytics (e.g., "DESC").
     * @return FetchArtifactsRes The response from the getArtifacts API call.
     * @throws Exception
     */
    public function getAnalytics(array $roomIds, string|null $roomSid = null, int $from = 0, int $limit = 20, string $orderBy = "DESC"): FetchArtifactsRes
    {
        return $this->getArtifacts($roomIds, $roomSid, RoomArtifactType::MEETING_ANALYTICS, $from, $limit, $orderBy);
    }

    /**
     * Generates a download link for analytics data.
     *
     * @param string $artifactId The ID of the analytics artifact to generate a download link for.
     * @return GetArtifactDownloadTokenRes The response from the getArtifactDownloadToken API call.
     * @throws Exception
     */
    public function getAnalyticsDownloadLink(string $artifactId): GetArtifactDownloadTokenRes
    {
        return $this->getArtifactDownloadToken($artifactId);
    }

    /**
     * Deletes analytics data.
     *
     * @param string $artifactId The ID of the analytics artifact to delete.
     * @return DeleteArtifactRes The response from the deleteArtifact API call.
     * @throws Exception
     */
    public function deleteAnalytics(string $artifactId): DeleteArtifactRes
    {
        return $this->deleteArtifact($artifactId);
    }

    /**
     * Retrieves the client files needed to build the plugNmeet interface.
     *
     * @return GetClientFilesRes The response from the getClientFiles API call.
     * @throws Exception
     */
    public function getClientFiles(): GetClientFilesRes
    {
        return $this->plugnmeet->getClientFiles();
    }

    /**
     * Formats raw analytics data into a more readable format.
     *
     * @param string|array $rawData The raw analytics data.
     * @param string $userTimezone The user's timezone.
     * @return AnalyticsFormatter An AnalyticsFormatter instance.
     */
    public static function getAnalyticsFormatter(string|array $rawData, string $userTimezone = 'UTC'): AnalyticsFormatter
    {
        if (!is_array($rawData)) {
            $rawData = json_decode($rawData, true);
        }
        return new AnalyticsFormatter($rawData, $userTimezone);
    }
}
