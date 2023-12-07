<?php

/*
 * Copyright (c) 2023 MynaParrot
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

// this is a simple example to handle webhooks data sent from plugNmeet

if (!isset($_SERVER["HTTP_AUTHORIZATION"]) && !isset($_SERVER["HTTP_HASH_TOKEN"])) {
    return;
}

$hash = isset($_SERVER["HTTP_AUTHORIZATION"]) ? $_SERVER["HTTP_AUTHORIZATION"] : $_SERVER["HTTP_HASH_TOKEN"];
if (empty($hash)) {
    return;
}

require __DIR__ . "/plugNmeetConnect.php";

$config = new stdClass();
$config->plugnmeet_server_url = "http://localhost:8080"; // host.docker.internal
$config->plugnmeet_api_key = "plugnmeet";
$config->plugnmeet_secret = "zumyyYWqv7KR2kUqvYdq4z4sXg7XTBD2ljT6";

$connect = new plugNmeetConnect($config);
$deco = $connect->getPlugnmeet()->decodeJWTData($hash);

if (!$deco || !isset($deco->sha256)) {
    return;
}

$entityBody = file_get_contents('php://input');
$our = hash('sha256', $entityBody, true);
$sentHash = base64_decode($deco->sha256);

if ($our !== $sentHash) {
    return;
}

// now we're good
$val = json_decode($entityBody, true);

//file_put_contents(__DIR__ . "/out.txt", print_r($val, true));

