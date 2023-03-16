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

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . "/plugNmeetConnect.php";

$config = new stdClass();
$config->plugnmeet_server_url = "http://localhost:8080";
$config->plugnmeet_api_key = "plugnmeet";
$config->plugnmeet_secret = "zumyyYWqv7KR2kUqvYdq4z4sXg7XTBD2ljT6";

$connect = new plugNmeetConnect($config);

$roomId = "room01"; // must be unique. You can also use $connect->getUUID();
$max_participants = 0; // value 0 means no limit (unlimited)
$user_full_name = "Your name";
// $userId = $connect->getUUID();
$userId = "Your-Unique-User-Id"; // must be unique for each user.
$isAdmin = true;

$roomMetadata = array(
    "room_features" => array(
        "allow_webcams" => true,
        "mute_on_start" => false,
        "allow_screen_share" => true,
        "allow_rtmp" => true,
        "allow_view_other_webcams" => true,
        "allow_view_other_users_list" => true,
        "admin_only_webcams" => false,
        "room_duration" => 0 // in minutes. 0 = no limit/unlimited
    ),
    "recording_features" => array(
        "is_allow" => true,
        "is_allow_cloud" => true,
        "is_allow_local" => true,
        "enable_auto_cloud_recording" => false
    ),
    "chat_features" => array(
        "allow_chat" => true,
        "allow_file_upload" => true
    ),
    "shared_note_pad_features" => array(
        "allowed_shared_note_pad" => true
    ),
    "whiteboard_features" => array(
        "allowed_whiteboard" => true
    ),
    "external_media_player_features" => array(
        "allowed_external_media_player" => true
    ),
    "waiting_room_features" => array(
        "is_active" => false,
    ),
    "breakout_room_features" => array(
        "is_allow" => true,
        "allowed_number_rooms" => 2
    ),
    "display_external_link_features" => array(
        "is_allow" => true,
    ),
    "ingress_features" => array(
        "is_allow" => true,
    ),
    "default_lock_settings" => array(
        "lock_microphone" => false,
        "lock_webcam" => false,
        "lock_screen_sharing" => true,
        "lock_whiteboard" => true,
        "lock_shared_notepad" => true,
        "lock_chat" => false,
        "lock_chat_send_message" => false,
        "lock_chat_file_share" => false,
        "lock_private_chat" => false // user can always send private message to moderator
    )
);
$isRoomActive = false;
$output = new stdClass();
$output->status = false;

try {
    $res = $connect->isRoomActive($roomId);
    $isRoomActive = $res->getStatus();
    $output->status = true;
    $output->msg = $res->getResponseMsg();
} catch (Exception $e) {
    $output->msg = $e->getMessage();
}

if (!$isRoomActive && $output->status) {
    try {
        $create = $connect->createRoom($roomId, "Test room", "Welcome to room", $max_participants, "", $roomMetadata);

        $isRoomActive = $create->getStatus();
        $output->status = $create->getStatus();
        $output->msg = $create->getResponseMsg();
    } catch (Exception $e) {
        $output->msg = $e->getMessage();
    }
}

if ($isRoomActive && $output->status) {
    try {
        $join = $connect->getJoinToken($roomId, $user_full_name, $userId, $isAdmin);

        if ($join->getStatus()) {
            $output->url = "<br>" . $config->plugnmeet_server_url . "?access_token=" . $join->getToken();
        }
        $output->status = $join->getStatus();
        $output->msg = $join->getResponseMsg();
    } catch (Exception $e) {
        $output->msg = $e->getMessage();
    }
}

echo "<pre>";
print_r($output);
