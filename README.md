# plugNmeet-sdk-php

Plug-N-Meet PHP SDK. You can use this SDK to make API requests to the Plug-N-Meet server from your PHP application.

Download the latest version from [release page](https://github.com/mynaparrot/plugNmeet-sdk-php/releases) or if you
prefer to use [composer](https://packagist.org/packages/mynaparrot/plugnmeet-sdk):

```bash
composer require mynaparrot/plugnmeet-sdk
```

Please check `examples` directory to see some examples.

## Methods/API

| Methods                                                                                                                                                  | Description                               |
|----------------------------------------------------------------------------------------------------------------------------------------------------------|-------------------------------------------|
| [createRoom](https://mynaparrot.github.io/plugNmeet-sdk-php/classes/Mynaparrot-Plugnmeet-PlugNmeet.html#method_createRoom)                               | To create new room                        |
| [getJoinToken](https://mynaparrot.github.io/plugNmeet-sdk-php/classes/Mynaparrot-Plugnmeet-PlugNmeet.html#method_getJoinToken)                           | Generate join token                       |
| [isRoomActive](https://mynaparrot.github.io/plugNmeet-sdk-php/classes/Mynaparrot-Plugnmeet-PlugNmeet.html#method_isRoomActive)                           | To check if room is active or not         |
| [getActiveRoomInfo](https://mynaparrot.github.io/plugNmeet-sdk-php/classes/Mynaparrot-Plugnmeet-PlugNmeet.html#method_getActiveRoomInfo)                 | Get active room information               |
| [getActiveRoomsInfo](https://mynaparrot.github.io/plugNmeet-sdk-php/classes/Mynaparrot-Plugnmeet-PlugNmeet.html#method_getActiveRoomsInfo)               | Get all active rooms                      |
| [fetchPastRoomsInfo](https://mynaparrot.github.io/plugNmeet-sdk-php/classes/Mynaparrot-Plugnmeet-PlugNmeet.html#method_fetchPastRoomsInfo)               | Get past  rooms                           |
| [endRoom](https://mynaparrot.github.io/plugNmeet-sdk-php/classes/Mynaparrot-Plugnmeet-PlugNmeet.html#method_endRoom)                                     | End active room                           |
| [fetchRecordings](https://mynaparrot.github.io/plugNmeet-sdk-php/classes/Mynaparrot-Plugnmeet-PlugNmeet.html#method_fetchRecordings)                     | Fetch recordings                          |
| [getRecordingInfo](https://mynaparrot.github.io/plugNmeet-sdk-php/classes/Mynaparrot-Plugnmeet-PlugNmeet.html#method_getRecordingInfo)                   | Get details of a recording                |
| [deleteRecordings](https://mynaparrot.github.io/plugNmeet-sdk-php/classes/Mynaparrot-Plugnmeet-PlugNmeet.html#method_deleteRecordings)                   | Delete recording                          |
| [getRecordingDownloadToken](https://mynaparrot.github.io/plugNmeet-sdk-php/classes/Mynaparrot-Plugnmeet-PlugNmeet.html#method_getRecordingDownloadToken) | Generate token to download recording      |
| [fetchAnalytics](https://mynaparrot.github.io/plugNmeet-sdk-php/classes/Mynaparrot-Plugnmeet-PlugNmeet.html#method_fetchAnalytics)                       | Fetch analytics info                      |
| [deleteAnalytics](https://mynaparrot.github.io/plugNmeet-sdk-php/classes/Mynaparrot-Plugnmeet-PlugNmeet.html#method_deleteAnalytics)                     | Delete Analytics                          |
| [getAnalyticsDownloadToken](https://mynaparrot.github.io/plugNmeet-sdk-php/classes/Mynaparrot-Plugnmeet-PlugNmeet.html#method_getAnalyticsDownloadToken) | Generate token to download analytics file |
| [getClientFiles](https://mynaparrot.github.io/plugNmeet-sdk-php/classes/Mynaparrot-Plugnmeet-PlugNmeet.html#method_getClientFiles)                       | Get client's files                        |
