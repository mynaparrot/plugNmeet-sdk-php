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

// this is a simple example to build plugNmeet client using API
// this way you can avoid to use iFrame to load the interface

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . "/plugNmeetConnect.php";

$config = new stdClass();
$config->plugnmeet_server_url = "http://localhost:8080"; // host.docker.internal
$config->plugnmeet_api_key = "plugnmeet";
$config->plugnmeet_secret = "zumyyYWqv7KR2kUqvYdq4z4sXg7XTBD2ljT6";

$connect = new plugNmeetConnect($config);
// https://www.plugnmeet.org/docs/api/get_client_files
$files = $connect->getClientFiles();

if (!$files->getStatus()) {
    die($files->getResponseMsg());
}

$jsFiles = $files->getJSFiles();
$cssFiles = $files->getCSSFiles();
$path = $config->plugnmeet_server_url . "/assets";

if (empty($jsFiles) || empty($cssFiles)) {
    die("didn't get required files to build interface");
}

$jsTag = "";
foreach ($jsFiles as $file) {
    $jsTag .= '<script src="' . $path . '/js/' . $file . '" defer="defer"></script>' . "\n\t";
}

$cssTag = "";
foreach ($cssFiles as $file) {
    $cssTag .= '<link href="' . $path . '/css/' . $file . '" rel="stylesheet" />' . "\n\t";
}

// build config
// https://github.com/mynaparrot/plugNmeet-client/blob/main/src/assets/config_sample.js
$js = 'window.PLUG_N_MEET_SERVER_URL = "' . $config->plugnmeet_server_url . '";';
$js .= 'window.STATIC_ASSETS_PATH = "' . $path . '";';

$js .= 'Window.ENABLE_DYNACAST = ' . filter_var(1, FILTER_VALIDATE_BOOLEAN) . ';';
$js .= 'window.ENABLE_SIMULCAST = ' . filter_var(1, FILTER_VALIDATE_BOOLEAN) . ';';
$js .= 'window.VIDEO_CODEC = "vp8";';
$js .= 'window.DEFAULT_WEBCAM_RESOLUTION = "h720";';
$js .= 'window.DEFAULT_SCREEN_SHARE_RESOLUTION = "h1080fps15";';
$js .= 'window.STOP_MIC_TRACK_ON_MUTE = ' . filter_var(1, FILTER_VALIDATE_BOOLEAN) . ';';

//$js .= 'window.CUSTOM_LOGO = "https://yourdomain.com/logo.png";';
$custom_design_items = array(
    "primary_color" => "#004D90",
    "secondary_color" => "#24AEF7",
    "background_color" => "#0b7db4",
    //"background_image" => "https://mydomain.com/custom_bg.png",
    "header_bg_color" => "#45b3ec",
    "footer_bg_color" => "#45b3ec",
    "left_side_bg_color" => "#04a2f3",
    "right_side_bg_color" => "#04a2f3",
    //"custom_css_url" => "https://mydomain.com/plugNmeet_desing.css",
    //"custom_logo" => "https://mydomain.com/logo.png"
);
$js .= 'window.DESIGN_CUSTOMIZATION = `' . json_encode($custom_design_items) . '`;';

$js = str_replace(";", ";\n\t", $js);
$cnfScript = "<script type=\"text/javascript\">\n\t" . $js . "</script>\n";

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <title>plugNmeet</title>
    <?php echo $cssTag . $jsTag . $cnfScript; ?>
</head>
<body>
<div id="plugNmeet-app"></div>
</body>
</html>
