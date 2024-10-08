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
$config->plugnmeet_server_url = "http://localhost:8080"; // host.docker.internal
$config->plugnmeet_api_key = "plugnmeet";
$config->plugnmeet_secret = "zumyyYWqv7KR2kUqvYdq4z4sXg7XTBD2ljT6";

$connect = new plugNmeetConnect($config);

$roomId = "room01"; // must be unique. You can also use $connect->getUUID();
$max_participants = 0; // value 0 means no limit (unlimited)
$user_full_name = "Your name";

// $userId = $connect->getUUID();
// you can ignore sending user id if
// auto_gen_user_id option was true during create room
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
        "enable_analytics" => true,
        "room_duration" => 0, // in minutes. 0 = no limit/unlimited
        "allow_virtual_bg" => true,
        "allow_raise_hand" => true,
        // if false then you'll need to provide unique user id
        "auto_gen_user_id" => true,
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
        "allowed_whiteboard" => true,
        //"preload_file" => "https://mydomain.com/text_book.pdf"
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
    "speech_to_text_translation_features" => array(
        "is_allow" => true,
        "is_allow_translation" => true,
    ),
    "end_to_end_encryption_features" => array(
        "is_enabled" => false,
        "included_chat_messages" => false,
        // this may use more CPU for the user end.
        // do not enable it unless really necessary
        "included_whiteboard" => false,
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
    ),
    // copyright_conf will only work if server config has been
    // set true for `allow_override` otherwise this will ignore
    "copyright_conf" => array(
        "display" => true,
        "text" => "Powered by <a href=\"https://www.plugnmeet.org\" target=\"_blank\">plugNmeet</a>"
    )
);
$isRoomActive = false;
$output = new stdClass();
$output->status = false;

try {
    $res = $connect->isRoomActive($roomId);
    if (!$res->getStatus()) {
        $output->msg = $res->getResponseMsg();
    } else {
        $isRoomActive = $res->isActive();
        $output->status = true;
    }

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
            $output->token = "<br>" . $join->getToken();
            $output->url = "<br>" . $config->plugnmeet_server_url . "?access_token=" . $join->getToken();
            // or you can set cookie name `pnm_access_token` with that token & redirect
        }
        $output->status = $join->getStatus();
        $output->msg = $join->getResponseMsg();
    } catch (Exception $e) {
        $output->msg = $e->getMessage();
    }
}

echo "<pre>";
print_r($output);
// you can also build your own page to load plugNmeet client
// in this case you can check example from conference.php file