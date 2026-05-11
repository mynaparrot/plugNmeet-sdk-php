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

// This is a simple example of how to build a plugNmeet client using the API.
// This approach avoids using an iFrame to load the interface.
//
// To generate a token, see the quickJoin.php file.
//
// The domain is localhost, so it will work with http.
// Development: http://localhost/conference.php?access_token=TOKEN_HERE
//
// During production, using HTTPS is compulsory; otherwise, users will not be able to join.
// Production: https://mydomain.com/conference.php?access_token=TOKEN_HERE
//
// You can use any location to build this page, but you must ensure that you have one of the following:
// - A GET parameter `access_token` with the token value.
// - A cookie named `pnm_access_token` with the value of the access token.
//
// You can add other parameters for your own use.
// The token value does not need to be encoded or changed in any way, as this will cause validation to fail.
//
// Note: To avoid conflicts, we recommend that you only use plugNmeet client files on this page.
// However, you are free to use any additional JS files you want, but please ensure that you have
// performed adequate testing before going to production.

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require __DIR__ . "/plugNmeetConnect.php";

$config = new stdClass();
$config->plugnmeet_server_url = "http://localhost:8080"; // host.docker.internal
$config->plugnmeet_api_key = "plugnmeet";
$config->plugnmeet_secret = "zumyyYWqv7KR2kUqvYdq4z4sXg7XTBD2ljT6";

$connect = new plugNmeetConnect($config);
// https://www.plugnmeet.org/docs/api/get-client-files
try {
    $res = $connect->getClientFiles();
} catch (Exception $e) {
    die($e->getMessage());
}

if (!$res->getStatus()) {
    die($res->getMsg());
}

$jsFiles = $res->getJsFiles();
$cssFiles = $res->getCssFiles();
$assetsPath = $config->plugnmeet_server_url . "/assets";

if (empty($jsFiles) || empty($cssFiles)) {
    die("didn't get required files to build interface");
}

if (!empty($res->getStaticAssetsPath())) {
    $assetsPath = $res->getStaticAssetsPath();
}

$jsTags = "";
$jsTagsPreload = "";
foreach ($jsFiles as $file) {
    if (str_starts_with($file, 'main-module.')) {
        $jsTags .= '<script src="' . $assetsPath . '/js/' . $file . '" type="module"></script>' . "\n";
    } else {
        $jsTags .= '<script src="' . $assetsPath . '/js/' . $file . '" defer="defer"></script>' . "\n";
    }
    if (str_contains($file, "runtime") || str_contains($file, "vendor")) {
        $jsTagsPreload .= '<link href="' . $assetsPath . '/js/' . $file . '" rel="preload" as="script" />' . "\n\t";
    }
}

$cssTags = "";
$cssTagsPreload = "";
foreach ($cssFiles as $file) {
    $cssTags .= '<link href="' . $assetsPath . '/css/' . $file . '" rel="stylesheet" />' . "\n\t";
    if (str_contains($file, "vendor")) {
        $cssTagsPreload .= '<link href="' . $assetsPath . '/css/' . $file . '" rel="preload" as="style" />' . "\n\t";
    }
}

// build config
// https://github.com/mynaparrot/plugNmeet-client/blob/main/src/assets/config_sample.js
$plugNmeetConfig = array(
    // The URL of your plugNmeet server.
    'serverUrl' => $config->plugnmeet_server_url,

    // This is helpful for external plugin development where images or other files are located
    // in another place.
    'staticAssetsPath' => $assetsPath,

    // Custom logos. For best results, use direct HTTPS links.
    /*'customLogo' => [
        'main_logo_light' => 'https://mydomain.com/logo_light.png',
        'main_logo_dark' => 'https://mydomain.com/logo_dark.png',
    ],*/

    // Dynacast dynamically pauses video layers that are not being consumed by any subscribers,
    // significantly reducing publishing CPU and bandwidth usage.
    'enableDynacast' => true,

    // When using simulcast, LiveKit will publish up to three versions of the stream at various resolutions.
    // The client can then pick the most appropriate one.
    'enableSimulcast' => true,

    // Available options: 'vp8' | 'h264' | 'vp9' | 'av1'. Default: 'vp8'.
    'videoCodec' => 'vp8',

    // Available options: 'h90' | 'h180' | 'h216' | 'h360' | 'h540' | 'h720' | 'h1080' | 'h1440' | 'h2160'.
    // Default: 'h720'.
    'defaultWebcamResolution' => 'h720',

    // Available options: 'h360fps3' | 'h720fps5' | 'h720fps15' | 'h1080fps15' | 'h1080fps30'.
    // Default: 'h1080fps15'.
    'defaultScreenShareResolution' => 'h1080fps15',

    // Available options: 'telephone' | 'speech' | 'music' | 'musicStereo' | 'musicHighQuality' | 'musicHighQualityStereo'.
    // Default: 'music'.
    'defaultAudioPreset' => 'music',

    // For local tracks, stop the underlying MediaStreamTrack when the track is muted (or paused).
    // On some platforms, this option is necessary to disable the microphone recording indicator.
    // Note: When this is enabled and BT devices are connected, they will transition between profiles
    // (e.g., HFP to A2DP), and there will be an audible difference in playback.
    'stopMicTrackOnMute' => true,

    // If true, the webcam view will be relocated and arranged based on the active speaker.
    // Default: true.
    'focusActiveSpeakerWebcam' => true,

    // Disables the dark mode theme and the user's ability to toggle it.
    'disableDarkMode' => false,

    // Design customization
    /*'designCustomization' => array(
        'primary_color' => '#004D90',
        'primary_btn_bg_color' => '#00a1f28c',
        'primary_btn_text_color' => '#ffffff',
        'secondary_color' => '#24AEF7',
        'secondary_btn_bg_color' => '#ffffff8c',
        'secondary_btn_text_color' => '#0c131a',
        'header_bg_color' => '#45b3ec',
        'footer_bg_color' => '#45b3ec',
        'footer_icon_bg_color' => '#004d90',
        'footer_icon_color' => '#ffffff',
        'side_panel_bg_color' => '#04a2f3',
        'background_color' => '#0b7db4',
        'background_image' => 'https://mydomain.com/custom_bg.png',
        'custom_css_url' => 'https://mydomain.com/plugNmeet_desing.css',
        'custom_logo' => 'https://mydomain.com/logo.png',
    ),*/

    // Whiteboard PreloadedLibraryItems, which should be an array of full library direct URLs.
    // You can get items from here: https://libraries.excalidraw.com
    /*'whiteboardPreloadedLibraryItems' => [
        'https://libraries.excalidraw.com/libraries/BjoernKW/UML-ER-library.excalidrawlib',
        'https://libraries.excalidraw.com/libraries/aretecode/decision-flow-control.excalidrawlib',
    ],*/

    // You can set default virtual background images here.
    // Make sure that you're using direct HTTPS links, otherwise the files may not load.
    /*'virtualBackgroundImages' => [
        'https://www.example.com/vb_bg/image1.png',
        'https://www.example.com/vb_bg/image2.png',
    ],*/

    // Databases older than this will be cleaned up on startup (in milliseconds).
    // Default: 6 hours.
    // 'dbMaxAgeMs' => 6 * 60 * 60 * 1000,
);

$jsonConfig = json_encode($plugNmeetConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
$js = "window.plugNmeetConfig = JSON.parse(`" . addslashes($jsonConfig) . "`);";
$cnfScript = "<script type=\"text/javascript\">\n" . $js . "\n</script>\n";

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width,initial-scale=1"/>
    <title>plugNmeet</title>
    <?php echo $cssTagsPreload . $jsTagsPreload . $cssTags . $cnfScript; ?>
</head>
<body>
<div id="plugNmeet-app"></div>
<?php echo $jsTags; ?>
</body>
</html>
