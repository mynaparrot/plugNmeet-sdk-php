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

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Mynaparrot\Plugnmeet\Parameters\CreateRoomParameters;
use Mynaparrot\Plugnmeet\Parameters\DeleteRecordingParameters;
use Mynaparrot\Plugnmeet\Parameters\EndRoomParameters;
use Mynaparrot\Plugnmeet\Parameters\FetchRecordingsParameters;
use Mynaparrot\Plugnmeet\Parameters\GenerateJoinTokenParameters;
use Mynaparrot\Plugnmeet\Parameters\RecordingDownloadTokenParameters;
use Mynaparrot\Plugnmeet\Parameters\GetActiveRoomInfoParameters;
use Mynaparrot\Plugnmeet\Parameters\IsRoomActiveParameters;
use Ramsey\Uuid\Uuid;
use RuntimeException;

/**
 *
 */
class PlugNmeet
{
    /**
     * @var string
     */
    protected $plugNmeet_URL;
    /**
     * @var string
     */
    protected $plugNmeet_API_Key;
    /**
     * @var string
     */
    protected $plugNmeet_Secret;
    /**
     * @var string
     */
    protected $defaultPath = "/auth";

    /**
     * @param $plugNmeet_URL
     * @param $plugNmeet_API_Key
     * @param $plugNmeet_Secret
     */
    public function __construct($plugNmeet_URL, $plugNmeet_API_Key, $plugNmeet_Secret)
    {
        $this->plugNmeet_URL = $plugNmeet_URL;
        $this->plugNmeet_API_Key = $plugNmeet_API_Key;
        $this->plugNmeet_Secret = $plugNmeet_Secret;
    }

    /**
     * @param CreateRoomParameters $createRoomParameters
     * @return mixed
     */
    public function createRoom(CreateRoomParameters $createRoomParameters)
    {
        $body = $createRoomParameters->buildBody();
        return $this->sendRequest("/room/create", $body);
    }

    /**
     * @param GenerateJoinTokenParameters $generateJoinTokenParameters
     * @return mixed
     */
    public function getJoinToken(GenerateJoinTokenParameters $generateJoinTokenParameters)
    {
        $body = $generateJoinTokenParameters->buildBody();
        return $this->sendRequest("/room/getJoinToken", $body);
    }

    /**
     * @param IsRoomActiveParameters $isRoomActiveParameters
     * @return mixed
     */
    public function isRoomActive(IsRoomActiveParameters $isRoomActiveParameters)
    {
        $body = $isRoomActiveParameters->buildBody();
        return $this->sendRequest("/room/isRoomActive", $body);
    }

    /**
     * @param GetActiveRoomInfoParameters $getActiveRoomInfoParameters
     * @return mixed
     */
    public function getActiveRoomInfo(GetActiveRoomInfoParameters $getActiveRoomInfoParameters)
    {
        $body = $getActiveRoomInfoParameters->buildBody();
        return $this->sendRequest("/room/getActiveRoomInfo", $body);
    }

    /**
     * @return mixed
     */
    public function getActiveRoomsInfo()
    {
        return $this->sendRequest("/room/getActiveRoomsInfo", []);
    }

    /**
     * @param EndRoomParameters $endRoomParameters
     * @return mixed
     */
    public function endRoom(EndRoomParameters $endRoomParameters)
    {
        $body = $endRoomParameters->buildBody();
        return $this->sendRequest("/room/endRoom", $body);
    }

    /**
     * @param FetchRecordingsParameters $fetchRecordingsParameters
     * @return mixed
     */
    public function fetchRecordings(FetchRecordingsParameters $fetchRecordingsParameters)
    {
        $body = $fetchRecordingsParameters->buildBody();
        return $this->sendRequest("/recording/fetch", $body);
    }

    /**
     * @param DeleteRecordingParameters $deleteRecordingParameters
     * @return mixed
     */
    public function deleteRecordings(DeleteRecordingParameters $deleteRecordingParameters)
    {
        $body = $deleteRecordingParameters->buildBody();
        return $this->sendRequest("/recording/delete", $body);
    }

    /**
     * @param RecordingDownloadTokenParameters $recordingDownloadTokenParameters
     * @return mixed
     */
    public function getRecordingDownloadToken(RecordingDownloadTokenParameters $recordingDownloadTokenParameters)
    {
        $body = $recordingDownloadTokenParameters->buildBody();
        return $this->sendRequest("/recording/getDownloadToken", $body);
    }


    /**
     * @param array $payload
     * @param int $validity in seconds
     * @param string $algo
     * @param array $head
     * @return string
     */
    public function getJWTencodedData(array $payload, int $validity, $algo = "HS256", array $head = [])
    {
        $payload['iss'] = $this->plugNmeet_API_Key;
        $payload['nbf'] = time();
        $payload['exp'] = time() + $validity;

        return JWT::encode($payload, $this->plugNmeet_Secret, 'HS256', null, $head);
    }

    /**
     * @param string $raw
     * @param string $algo
     * @return object
     */
    public function decodeJWTData(string $raw, $algo = "HS256")
    {
        return JWT::decode($raw, new Key($this->plugNmeet_Secret, $algo));
    }

    /**
     * @return string
     */
    public function getUUID()
    {
        $uuid = Uuid::uuid4();
        return $uuid->toString();
    }

    /**
     * @param $path
     * @param array $body
     * @return mixed
     */
    protected function sendRequest($path, array $body)
    {
        $header = array(
            "Content-type: application/json",
            "API-KEY: " . $this->plugNmeet_API_Key,
            "API-SECRET: " . $this->plugNmeet_Secret
        );
        $url = $this->plugNmeet_URL . $this->defaultPath . $path;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . '/cert/cacert.pem');

        $result = curl_exec($ch);
        $error = curl_error($ch);
        $errno = curl_errno($ch);
        curl_close($ch);

        if (0 !== $errno) {
            throw new RuntimeException($error, $errno);
        }

        return json_decode($result);
    }
}
