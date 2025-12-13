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

use Google\Protobuf\Internal\DescriptorPool;
use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\MapField;
use Mynaparrot\Plugnmeet\PlugNmeet;
use Mynaparrot\PlugnmeetProto\ArtifactInfoReq;
use Mynaparrot\PlugnmeetProto\ArtifactInfoRes;
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
use Mynaparrot\PlugnmeetProto\RoomCreateFeatures;
use Mynaparrot\PlugnmeetProto\RoomEndReq;
use Mynaparrot\PlugnmeetProto\RoomEndRes;
use Mynaparrot\PlugnmeetProto\RoomMetadata;
use Mynaparrot\PlugnmeetProto\UpdateRecordingMetadataReq;
use Mynaparrot\PlugnmeetProto\UpdateRecordingMetadataRes;
use Mynaparrot\PlugnmeetProto\UserInfo;
use Mynaparrot\PlugnmeetProto\UserMetadata;

require "../vendor/autoload.php";

/**
 *
 */
class plugNmeetConnect
{
    /**
     * @var PlugNmeet
     */
    protected PlugNmeet $plugnmeet;

    /**
     * @param stdClass $config
     * @param int $timeout
     * @param bool $verifySSL
     */
    public function __construct(stdClass $config, int $timeout = 60, bool $verifySSL = true)
    {
        $this->plugnmeet = new PlugNmeet(
            $config->plugnmeet_server_url,
            $config->plugnmeet_api_key,
            $config->plugnmeet_secret,
            $timeout,
            $verifySSL
        );
    }

    /**
     * @return PlugNmeet
     */
    public function getPlugnmeet(): PlugNmeet
    {
        return $this->plugnmeet;
    }

    /**
     * @return string
     */
    public function getUUID(): string
    {
        return $this->plugnmeet->getUUID();
    }

    /**
     * Generate UUID v4 random string
     *
     * @return string
     */
    public static function generateUuid4(): string
    {
        return PlugNmeet::generateUuid4();
    }

    /**
     * @param string $roomId
     * @param string $roomTitle
     * @param array $roomMetadata
     * @param string $welcomeMessage
     * @param string $logoutUrl
     * @param string $webHookUrl
     * @param int $max_participants
     * @param int $empty_timeout
     * @param string|null $extraData
     * @return CreateRoomRes
     * @throws Exception
     */
    public function createRoom(string $roomId, string $roomTitle, array $roomMetadata, string $welcomeMessage = "", string $logoutUrl = "", string $webHookUrl = "", int $max_participants = 0, int $empty_timeout = 0, string $extraData = null): CreateRoomRes
    {
        if (!isset($roomMetadata['room_features']) || !is_array($roomMetadata['room_features'])) {
            throw new Exception("room_features required and should be an array");
        }

        // Features can be passed in `room_features` or as top-level keys in `$roomMetadata`.
        // We're doing this with all of our plugins
        // We'll merge them, with `room_features` taking precedence.
        $roomMetadataFeatures = $roomMetadata['room_features'];
        foreach ($roomMetadata as $k => $data) {
            if ($k === "room_features" || $k === "default_lock_settings" || $k === "copyright_conf") {
                continue;
            }
            if (!isset($roomMetadataFeatures[$k])) {
                $roomMetadataFeatures[$k] = $data;
            }
        }

        // Dynamically convert data types to match the protobuf message definition.
        $sanitizedFeatures = $this->_convertDataToProtoJsonArray($roomMetadataFeatures, RoomCreateFeatures::class);

        $features = new RoomCreateFeatures();
        $features->mergeFromJsonString(json_encode($sanitizedFeatures), true);

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
     * @param string $roomId
     * @param string $name
     * @param string $userId
     * @param bool $isAdmin
     * @param bool $isHidden
     * @param UserMetadata|null $userMetadata
     * @param LockSettings|null $lockSettings
     * @return GenerateTokenRes
     * @throws Exception
     */
    public function getJoinToken(string $roomId, string $name, string $userId, bool $isAdmin, bool $isHidden = false, UserMetadata $userMetadata = null, LockSettings $lockSettings = null): GenerateTokenRes
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
     * @param string $roomId
     * @return IsRoomActiveRes
     * @throws Exception
     */
    public function isRoomActive(string $roomId): IsRoomActiveRes
    {
        $isRoomActiveReq = new IsRoomActiveReq();
        $isRoomActiveReq->setRoomId($roomId);

        return $this->plugnmeet->isRoomActive($isRoomActiveReq);
    }

    /**
     * @param string $roomId
     * @return GetActiveRoomInfoRes
     * @throws Exception
     */
    public function getActiveRoomInfo(string $roomId): GetActiveRoomInfoRes
    {
        $getActiveRoomInfoReq = new GetActiveRoomInfoReq();
        $getActiveRoomInfoReq->setRoomId($roomId);

        return $this->plugnmeet->getActiveRoomInfo($getActiveRoomInfoReq);
    }

    /**
     * @return GetActiveRoomsInfoRes
     * @throws Exception
     */
    public function getActiveRoomsInfo(): GetActiveRoomsInfoRes
    {
        return $this->plugnmeet->getActiveRoomsInfo();
    }

    /**
     * @param string $roomId
     * @return RoomEndRes
     * @throws Exception
     */
    public function endRoom(string $roomId): RoomEndRes
    {
        $roomEndReq = new RoomEndReq();
        $roomEndReq->setRoomId($roomId);

        return $this->plugnmeet->endRoom($roomEndReq);
    }

    /**
     * @param array $roomIds
     * @param int $from
     * @param int $limit
     * @param string $orderBy
     * @return FetchPastRoomsRes
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
     * @param array $roomIds
     * @param string|null $roomSid
     * @param int $from
     * @param int $limit
     * @param string $orderBy
     * @return FetchRecordingsRes
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
     * @param string $recordingId
     * @return RecordingInfoRes
     * @throws Exception
     */
    public function getRecordingInfo(string $recordingId): RecordingInfoRes
    {
        $recordingInfoReq = new RecordingInfoReq();
        $recordingInfoReq->setRecordId($recordingId);

        return $this->plugnmeet->getRecordingInfo($recordingInfoReq);
    }

    /**
     * @param string $recordingId
     * @param string $title
     * @param string|null $description
     * @param array|MapField $extraData
     * @return UpdateRecordingMetadataRes
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
     * @param string $recordingId
     * @return GetDownloadTokenRes
     * @throws Exception
     */
    public function getRecordingDownloadLink(string $recordingId): GetDownloadTokenRes
    {
        $getDownloadTokenReq = new GetDownloadTokenReq();
        $getDownloadTokenReq->setRecordId($recordingId);

        return $this->plugnmeet->getRecordingDownloadToken($getDownloadTokenReq);
    }

    /**
     * @param string $recordingId
     * @return DeleteRecordingRes
     * @throws Exception
     */
    public function deleteRecording(string $recordingId): DeleteRecordingRes
    {
        $deleteRecordingReq = new DeleteRecordingReq();
        $deleteRecordingReq->setRecordId($recordingId);

        return $this->plugnmeet->deleteRecordings($deleteRecordingReq);
    }

    /**
     * @param array $roomIds
     * @param string|null $roomSid
     * @param int $from
     * @param int $limit
     * @param string $orderBy
     * @return FetchArtifactsRes
     * @throws Exception
     */
    public function getArtifacts(array $roomIds, string|null $roomSid = null, int|null $artifactsType = null, int $from = 0, int $limit = 20, string $orderBy = "DESC"): FetchArtifactsRes
    {
        $fetchRecordingsReq = new FetchArtifactsReq();
        $fetchRecordingsReq->setRoomIds($roomIds);
        if (!is_null($roomSid)) {
            $fetchRecordingsReq->setRoomSid($roomSid);
        }
        if (!is_null($artifactsType)) {
            $fetchRecordingsReq->setType($artifactsType);
        }
        $fetchRecordingsReq->setFrom($from);
        $fetchRecordingsReq->setLimit($limit);
        $fetchRecordingsReq->setOrderBy($orderBy);

        return $this->plugnmeet->fetchArtifacts($fetchRecordingsReq);
    }

    /**
     * @param string $artifactId
     * @return ArtifactInfoRes
     * @throws Exception
     */
    public function getArtifactInfo(string $artifactId): ArtifactInfoRes
    {
        $recordingInfoReq = new ArtifactInfoReq();
        $recordingInfoReq->setArtifactId($artifactId);

        return $this->plugnmeet->getArtifactInfo($recordingInfoReq);
    }

    /**
     * @param string $artifactId
     * @return GetArtifactDownloadTokenRes
     * @throws Exception
     */
    public function getArtifactDownloadToken(string $artifactId): GetArtifactDownloadTokenRes
    {
        $getDownloadTokenReq = new GetArtifactDownloadTokenReq();
        $getDownloadTokenReq->setArtifactId($artifactId);

        return $this->plugnmeet->getArtifactDownloadToken($getDownloadTokenReq);
    }

    /**
     * @param string $artifactId
     * @return DeleteArtifactRes
     * @throws Exception
     */
    public function deleteArtifact(string $artifactId): DeleteArtifactRes
    {
        $deleteRecordingReq = new DeleteArtifactReq();
        $deleteRecordingReq->setArtifactId($artifactId);

        return $this->plugnmeet->deleteArtifact($deleteRecordingReq);
    }

    /**
     * @param array $roomIds
     * @param string|null $roomSid
     * @param int $from
     * @param int $limit
     * @param string $orderBy
     * @return FetchArtifactsRes
     * @throws Exception
     */
    public function getAnalytics(array $roomIds, string|null $roomSid = null, int $from = 0, int $limit = 20, string $orderBy = "DESC"): FetchArtifactsRes
    {
        return $this->getArtifacts($roomIds, $roomSid, RoomArtifactType::MEETING_ANALYTICS, $from, $limit, $orderBy);
    }

    /**
     * @param string $artifactId
     * @return GetArtifactDownloadTokenRes
     * @throws Exception
     */
    public function getAnalyticsDownloadLink(string $artifactId): GetArtifactDownloadTokenRes
    {
        return $this->getArtifactDownloadToken($artifactId);
    }

    /**
     * @param string $artifactId
     * @return DeleteArtifactRes
     * @throws Exception
     */
    public function deleteAnalytics(string $artifactId): DeleteArtifactRes
    {
        return $this->deleteArtifact($artifactId);
    }

    /**
     * @return GetClientFilesRes
     * @throws Exception
     */
    public function getClientFiles(): GetClientFilesRes
    {
        return $this->plugnmeet->getClientFiles();
    }

    /**
     * Converts a user-provided array (with snake_case keys) into a JSON-like
     * array (with camelCase keys and correctly typed values) suitable for
     * Protobuf's mergeFromJsonString method.
     *
     * This method recursively processes nested message structures and correctly
     * omits optional string fields that are empty. It leverages the Protobuf
     * message's setters and getters to handle type conversions, ensuring that
     * values are cast to the correct type as defined in the .proto file.
     *
     * @param array $data The input array with snake_case keys.
     * @param string $protoClassFqn The fully qualified class name of the Protobuf message.
     *
     * @return array The sanitized, JSON-like array with camelCase keys.
     * @throws Exception
     * @since 2.0.0
     */
    private function _convertDataToProtoJsonArray(array $data, string $protoClassFqn): array
    {
        // This ensures the class's metadata is loaded into the pool.
        if (!class_exists($protoClassFqn)) {
            throw new Exception("Protobuf class not found: " . $protoClassFqn);
        }
        $messageInstance = new $protoClassFqn();

        $pool = DescriptorPool::getGeneratedPool();
        $desc = $pool->getDescriptorByClassName($protoClassFqn);

        if (!$desc) {
            return [];
        }

        $result = [];
        foreach ($data as $key => $value) {
            $field = $desc->getFieldByName($key);

            if (!$field) {
                continue;
            }

            $jsonKey = $field->getJsonName();
            $type = $field->getType();

            if ($type === GPBType::MESSAGE) {
                if (is_array($value) && !empty($value)) {
                    $subMessageClass = $field->getMessageType()->getClass();
                    $subResult = $this->_convertDataToProtoJsonArray($value, $subMessageClass);
                    if (!empty($subResult)) {
                        $result[$jsonKey] = $subResult;
                    }
                }
            } elseif ($type === GPBType::STRING && $value === '') {
                // Omit optional fields that have empty string values.
                // Do nothing.
                continue;
            } else {
                $setter = $field->getSetter();
                $getter = $field->getGetter();

                $messageInstance->$setter($value);
                $result[$jsonKey] = $messageInstance->$getter();
            }
        }

        return $result;
    }
}
