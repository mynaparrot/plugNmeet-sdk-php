# Changelog

## [3.0.0](https://github.com/mynaparrot/plugNmeet-sdk-php/compare/v2.2.2...v3.0.0) (2026-07-09)


### ⚠ BREAKING CHANGES

* project migrated to use `Protocol buffers`

### Features

* added new `broadcastToRoom` and `uploadWhiteboardFile` endpoint ([b0cedf3](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/b0cedf35706e20c71190565dd07447794420645c))
* analytics ([38b6388](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/38b6388fdd7308645a4a1ea4927de4b754927f99))
* analytics ([fc3c5d8](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/fc3c5d89f9738fa10cc69e81b9ffed77d2ead3f5))
* API Recording info ([4ef341e](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/4ef341e7f3e7152012a8e453594e642618cb8733))
* **API:** added new API endpoint `mergeRecordings` ([311a7e0](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/311a7e00270cb907cf493a96b0f58ae29da38dd5))
* artifacts API ([486a209](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/486a2090256d8652f13d21a32e29a64504a177a6))
* auto recording ([2d21504](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/2d21504689ea1f6796ecd2b1582d5fc301f3da5b))
* auto recording ([7e45d28](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/7e45d286fddb17222be7ce2e750fb0989d9eab06))
* auto_gen_user_id ([8e51278](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/8e51278978dec4bc9f79a41a436c95e382925165))
* auto_gen_user_id ([ac5ca5d](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/ac5ca5d24805621e36d898800bc43a178dc6651f))
* breakout rooms ([8d6642b](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/8d6642bbf4066bbfa65de8ea4d5ec903d699528e))
* control user webcam to be record ([2ab00c5](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/2ab00c564a44a3879a50e01641067e299026c319))
* Display External Link Features ([a84d935](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/a84d935977355659c0542fae44bf557fa2f527a5))
* dropped support for PHP 7.4 ([d09739c](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/d09739cd47fd9c2248298fd62e7d5146ab9f97d3))
* External Media Player + improvements ([78ae606](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/78ae6068e13f0a3246c7f3a9d62d1061f167f37c))
* getClientFiles ([2bc8efb](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/2bc8efbce2cf9a5b5dff55715c117c0c8ebd6975))
* ingress ([bf1fcac](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/bf1fcac1bc267e5a7610c2f77666e941973674ea))
* option to disable `virtualBackgrounds` & `raiseHand` ([e727e5e](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/e727e5ec16a432487c907ed40fbeb77bad1afae9))
* project migrated to use `Protocol buffers` ([2fcc4d8](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/2fcc4d85da570cc3a1bea9f76240e7d296b0854a))
* recording metadata update ([f89d275](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/f89d275e892a346192b420068b4f8abdd9947ec7))
* SIP/VoIP dial in ([8625d73](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/8625d732b2f8da58275ea321cd07d402b709fafb))
* speech to text/translation ([5bd77e2](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/5bd77e28c9c24363fbec023665df016a8caea199))
* waiting room + polls + room duration ([a96fc30](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/a96fc30872cf19d66d400ffcbb023c201836c553))
* whiteboard + shared notepad ([8d3cab4](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/8d3cab4ae8482238232f9f3ac37fdcec3fad18bd))
* whiteboard preload file ([61354f1](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/61354f1373914595ce1210dd6974095ab85fda22))


### Bug Fixes

* added `.gitattributes` file ([25bac4c](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/25bac4c7b43a54c4bf05e4fa42362be1cfc385da))
* added helper class to simplify/formate analytics data ([8f29d3d](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/8f29d3d5e82bab838a731cd1134da1c6642816f6))
* added more clear docs ([8ba79cf](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/8ba79cf6ae6c2e09dfe5929548ef145d6e3a8e05))
* added new option `disableDarkMode` & `right_panel_bg_color` ([bdd30d5](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/bdd30d50414d363af2eea6a97007f04e7a65cc87))
* added new options ([2537d78](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/2537d78ea60752b6fa127c9b7c7a1d9d9ba94d01))
* allow to set custom HttpClient ([da72cb6](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/da72cb6e14401f2e62283fa5882e4268f8de0a40))
* better error handling ([74b7b70](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/74b7b70b56fa1e19178f1a8039d14aa38f184ce2))
* bump deps ([25dafa3](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/25dafa3704ff5a1350786633f7de831a802afc0f))
* bump proto ([bc6654d](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/bc6654d109f62a95df40e4a01b652bf17ec913e7))
* bump proto ([9bcad14](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/9bcad14aad161066ba01f00252eaa9f02bd39cae))
* bump proto ([fb874ad](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/fb874ade56b2a57d5d2a9208e1faf045bedf5b95))
* bump proto ([fe69010](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/fe69010510eca012dd5bddb540a4c49125f47fda))
* bump proto ([5764cf2](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/5764cf2631657e0662d113244bf9217e22033b64))
* bump proto ([42177f1](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/42177f19570ca23b4a25b90227527a39ccb75733))
* bump proto ([5e9eb7f](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/5e9eb7f70d02e2941b657ea4eff045d28c41728b))
* bump proto ([2b64600](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/2b646002da2faa68db56fc5319671a2ad073f6f4))
* bump proto ([472f007](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/472f00704f9c3c782ab3ca6155cbff0ba9eadeb0))
* bump proto ([738238c](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/738238c45eb7a3f94a4629417fd83986f9bc6740))
* bump proto ([ca7a9ac](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/ca7a9ac72a46c03c8424816bb05318451bda8fc4))
* bump protocol ([79e6157](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/79e615792087a9079014e33e3d1c8caa72e4396b))
* **ci:** added release-please-action ([4498a14](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/4498a147ba54bb911acfd25951d945276d61ebde))
* **ci:** try to fix build ([4d81a49](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/4d81a4966481262d736fac88f43393b39c0200b0))
* **ci:** update PHP version ([7000cea](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/7000cea81401596f8299d575c5f62baf0b9f4596))
* converting JSON values to correct type ([7622ecf](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/7622ecfb06d50b1a378f4317ea582458d1e8008f))
* **deps:** update dependency firebase/php-jwt to v6.11.0 ([e5a3697](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/e5a3697e3305585341ebe37bc1f425978ab08879))
* **deps:** update dependency firebase/php-jwt to v6.11.0 ([9cbad82](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/9cbad828a6256e7401f8acdbd9363354841f4fb3))
* **deps:** update dependency firebase/php-jwt to v6.11.1 ([d8cd979](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/d8cd979deff62664fc5ac3e68dcb5fd471b5f552))
* **deps:** update dependency firebase/php-jwt to v6.11.1 ([0c46095](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/0c46095e69feea58b42e0afa429a5ab5ac101f56))
* **deps:** update dependency firebase/php-jwt to v7.0.3 ([3473b34](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/3473b3428ba06c2ae2bee704747c12bcd9bc0ac6))
* **deps:** update dependency firebase/php-jwt to v7.0.3 ([c457267](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/c4572674799c2c2aefbfa3f871149c907c28762a))
* **deps:** update dependency firebase/php-jwt to v7.0.5 ([521ef57](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/521ef5751ab83f127bc35d67a91cff9d18db2f70))
* **deps:** update dependency firebase/php-jwt to v7.0.5 ([b8b4437](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/b8b4437a7a8a863de11ff6377929aead18d3f1ac))
* **deps:** update dependency firebase/php-jwt to v7.1.0 ([fe121df](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/fe121df79199927aed857a83e8947094e94c445e))
* **deps:** update dependency firebase/php-jwt to v7.1.0 ([898e89e](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/898e89e49435cc6c41637f3f47194f61d3cfe95c))
* **deps:** update dependency google/protobuf to v4.33.2 ([1eafcab](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/1eafcab6f7cb03f042e29e16db606c923ea2fc3a))
* **deps:** update dependency google/protobuf to v4.33.2 ([38ea2af](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/38ea2af8403c0b27026f6a79af2afb7818bc552f))
* **deps:** update dependency google/protobuf to v4.33.4 ([f274542](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/f2745428e5777b30e722224fe7c09f7ee97b380d))
* **deps:** update dependency google/protobuf to v4.33.4 ([4240c2c](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/4240c2c90a2476fa4a7394de71757cb6de45dc59))
* **deps:** update dependency google/protobuf to v4.33.5 ([31fde1d](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/31fde1dc3559f013a99c55cf6ce12e5608c81fed))
* **deps:** update dependency google/protobuf to v4.33.5 ([093de05](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/093de05150b29690e0e8c68345bf1133e2d3c1bc))
* **deps:** update dependency google/protobuf to v4.33.6 [security] ([f912017](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/f91201757acb51f1d5d9d03799fefec9085d3daa))
* **deps:** update dependency google/protobuf to v4.33.6 [security] ([b45be5f](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/b45be5f99fd3e3e35dff31860f2a6677537e07b6))
* **deps:** update dependency google/protobuf to v5 ([727132c](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/727132c67cd3bf2601ce149a909576da081e84be))
* **deps:** update dependency google/protobuf to v5 ([84767a9](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/84767a994c44304d73ec578f136b7f478147d974))
* **deps:** update dependency google/protobuf to v5.35.0 ([cbb1653](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/cbb16535ec93ad7efbf0d6d95f225162eca5ccd8))
* **deps:** update dependency google/protobuf to v5.35.0 ([cae3a89](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/cae3a8906f379f69dd058096fa4a4954031dde7c))
* **deps:** update dependency google/protobuf to v5.35.1 ([af1f6a1](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/af1f6a18ab16676a55379b078c686e825e208b2e))
* **deps:** update dependency google/protobuf to v5.35.1 ([44ded62](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/44ded62f8ffc1a4336af016e641615ba4da0dcc6))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.1 ([11cfbd3](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/11cfbd3edd30c46cb417a54aae190d641eaca2f7))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.1 ([0b4c13b](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/0b4c13bfa4121f5f3ea4dc1509d0ca6d19032869))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.2 ([58fd8d2](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/58fd8d2c0d9838657e0aa6e41a296c541eabf1c2))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.2 ([ca8cedf](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/ca8cedfced99d6c5d5333ec11fb15d956ad87ac1))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.3 ([3e3d077](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/3e3d07774e0e5aa076006a149e84872a4ab6988b))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.3 ([c815a51](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/c815a51ab695311dfbc8cce7d15bd7eddf1516c2))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.4 ([621b91e](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/621b91e908c21cb9e2ec7788cdcd8c8ebeede514))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.4 ([30cd0b4](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/30cd0b44395e91651096fdca86ba2affbc313157))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.5 ([4513159](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/45131594b938ec2408cad077698557fe288c9e28))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.5 ([914c43c](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/914c43c5fae7c1a67cce206ad8c7a43050707efa))
* **deps:** update dependency guzzlehttp/guzzle to v7.11.0 ([66ec0be](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/66ec0be43d0b0af938c5cef8cc92394aaa1a62c2))
* **deps:** update dependency guzzlehttp/guzzle to v7.11.0 ([20a2fdd](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/20a2fdd56032bf570fe6a5655cd3586714df20f8))
* **deps:** update dependency guzzlehttp/guzzle to v7.12.1 [security] ([45db074](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/45db074899c8c1545c6f7c5726c5b7a3c8bd2621))
* **deps:** update dependency guzzlehttp/guzzle to v7.12.1 [security] ([6fa0d2f](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/6fa0d2f86f2085349db08253586cd94889e3ee38))
* **deps:** update dependency guzzlehttp/guzzle to v7.12.3 ([eb12a61](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/eb12a61293ecb6eb33839bb097b6f974a3a690d9))
* **deps:** update dependency guzzlehttp/guzzle to v7.12.3 ([193c48b](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/193c48bf42d8f8225c55a2218609920e5afc6b91))
* **deps:** update dependency guzzlehttp/guzzle to v7.13.2 ([1b5e320](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/1b5e32003e2e200faba7b639127db511210f380a))
* **deps:** update dependency guzzlehttp/guzzle to v7.13.2 ([984c7fa](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/984c7fa20b3d77ca7196fda5e125a7fad6c81d43))
* **deps:** update dependency guzzlehttp/guzzle to v7.13.3 ([4803fc1](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/4803fc10c4d98f19ebcf64848fedacf820df1d6c))
* **deps:** update dependency guzzlehttp/guzzle to v7.13.3 ([0e63e3d](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/0e63e3d198e20b72c0d6c202f001bd9e2971eb0c))
* **deps:** update dependency guzzlehttp/guzzle to v7.14.0 ([34a9955](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/34a9955baf39e28d2ca2583049c03baf8b9f1b30))
* **deps:** update dependency ramsey/uuid to v4.9.3 ([652194d](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/652194d78027e9e1204f137ef3babad0080f0a83))
* **deps:** update dependency ramsey/uuid to v4.9.3 ([8e702fd](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/8e702fd391db56e5d3a4719c5a287deab8b4c89c))
* don't include other files ([df10626](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/df1062691a9fb787986057b7c5313fb455d1ab0c))
* exclude `composer.lock` from archive ([f139ec3](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/f139ec3e8a526678b1899103f05dedb0f8d1a9cc))
* handle error message better way ([f321855](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/f321855a509c29845232ce6ca036159193fb98f8))
* handle network error related messages better way ([adc33ac](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/adc33ac80c04ea83f901731f51fc7eeda1ab5448))
* just path the path and client to decide what to do ([2e3e6dd](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/2e3e6dd338c3cd054f0c0e77dc9aeeca784b04a4))
* link update ([5047809](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/50478093be1eeeb92d36509391118a6ffb51a15c))
* lint ([2529fc0](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/2529fc0c6d05ba22cf524984f8559c95be4a2d90))
* lint ([5335f56](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/5335f5666441b381c90209524f2cadad1c40e074))
* lint ([e55e6ca](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/e55e6ca7d7317385613c5665d48a954d80da0e66))
* lint ([1603c91](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/1603c91730b2ffc39c24c33e050411891f5e072d))
* lint ([53535d1](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/53535d15ae272c3f0624b11bb85a75bfff27c6b7))
* migrate project to use `GuzzleHttp` library ([236dfce](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/236dfce3e503b779a9872bfe17238fd32c9ea97f))
* prep for new client ([293d53e](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/293d53e075e581d7a06328ebc33d77d321c202fd))
* prep for new client ([e4bab4c](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/e4bab4cff6ec4dc947863be3477ce7e310d49585))
* **refactor:** Builds a Protobuf message object from array directly ([f15697a](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/f15697afc9fced41adb1a5d9acdb9190cf46a263))
* **refactor:** use Protobuf message's setters and getters to handle type conversions ([dc0235e](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/dc0235e473e3d6a02b268bb05711ccd993bf1b0a))
* set default `$timeout` to 60 seconds and configurable ([bbc0ff8](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/bbc0ff85011a0a8c4879a1f688da9660bfbe0ca3))
* to use `staticAssetsPath` CDN host ([58110df](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/58110dfe1a4dc1b9cf252746e22ce970fb285b70))
* update deps ([309848d](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/309848ddac9b8352da8405fa0149afc41e012291))


### Miscellaneous Chores

* Configure Renovate ([867b30f](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/867b30f00a446a34f1769286c72d54004bdcede7))
* **deps:** update actions/checkout action to v4 ([3a4831b](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/3a4831bed9166728a7b54b544ac6b07fcdfefae5))
* **deps:** update actions/checkout action to v4 ([c754b50](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/c754b50b01b3b09a8a8c4413fe444c93b9a99ff3))
* **deps:** update actions/checkout action to v5 ([61421b1](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/61421b1e06f68def63bcec6c9431ec8703ebf04c))
* **deps:** update actions/checkout action to v5 ([a6a4196](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/a6a4196d21521a5fbbffa6a1503469a7abbf2718))
* **deps:** update actions/checkout action to v7 ([993ce9a](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/993ce9ad6e4e3e9a14001af49d29ad6979475a81))
* **deps:** update actions/checkout action to v7 ([61f2e3b](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/61f2e3bd10f482d3554ff26deb13e6aedf8f6ff8))
* **deps:** update dependency overtrue/phplint to v9.5.5 ([47c3ec7](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/47c3ec78e5c945added9eee4593fbf2c382ec9f1))
* **deps:** update dependency overtrue/phplint to v9.5.5 ([52dc313](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/52dc3134eef5ffea87382c2fa2ac26ffb7c7813c))
* **deps:** update dependency overtrue/phplint to v9.5.6 ([30fd598](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/30fd598752eda80398b8476fcd1fb2392717a9c9))
* **deps:** update dependency overtrue/phplint to v9.5.6 ([1bf4d08](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/1bf4d08e3f597f8fe07872f09f74813bd135b82d))
* **deps:** update dependency overtrue/phplint to v9.7.2 ([17092b1](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/17092b1a1747380845bf2ff7461c6bb1fb955334))
* **deps:** update dependency overtrue/phplint to v9.7.2 ([8a6263a](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/8a6263a11703947397ede8b771af919b9677436b))
* **deps:** update dependency squizlabs/php_codesniffer to v3.11.1 ([f4536f2](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/f4536f2fe674ede0a64aa705fe3619edb7c4286a))
* **deps:** update dependency squizlabs/php_codesniffer to v3.11.1 ([18f64c9](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/18f64c993503edbf882085d671ca899020460714))
* **deps:** update dependency squizlabs/php_codesniffer to v3.11.2 ([3df31ed](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/3df31ed4cea643953904698ef4acd189e9153941))
* **deps:** update dependency squizlabs/php_codesniffer to v3.11.2 ([13d9505](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/13d9505f954656f063909ca620e8d113ae24664f))
* **deps:** update dependency squizlabs/php_codesniffer to v3.11.3 ([909151b](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/909151b7459fc04a228ae8a968177c4075455318))
* **deps:** update dependency squizlabs/php_codesniffer to v3.11.3 ([1f42e38](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/1f42e38c9a94ea2ac98ec9e40ea1939c08014e3a))
* **deps:** update googleapis/release-please-action action to v5 ([df22872](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/df22872ff4bb685b5d157505cfefe780486bd7df))
* **deps:** update googleapis/release-please-action action to v5 ([396665b](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/396665b95bd1f80981705c608480b223d8b08b03))
* **deps:** update peaceiris/actions-gh-pages action to v4 ([6ead7f9](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/6ead7f95e1334be8c033af90bd344451ad995265))
* **deps:** update peaceiris/actions-gh-pages action to v4 ([3504eb5](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/3504eb50416a0d96a1a012b5f99caa0b392e67d6))
* **main:** release 1.6.0 ([11eb2d0](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/11eb2d08f3a0925af4c979f9295616e123f81627))
* **main:** release 1.6.0 ([00b74fb](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/00b74fb96ed53965edaf61b86f99427cc55fce37))
* **main:** release 1.6.1 ([5f61060](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/5f610606cab671bb59145ec456f443070f10b1db))
* **main:** release 1.6.1 ([b4d4bab](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/b4d4babe446e030962efacbf61d404734126dc30))
* **main:** release 1.6.2 ([91c3ae9](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/91c3ae9f0b35457310a8dacda637ce8f59c65084))
* **main:** release 1.6.2 ([6841e46](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/6841e464a955fa876111af113a3247f1b409d7b0))
* **main:** release 2.0.0 ([24abd16](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/24abd1601b247e8ebe246d603a3bbca87ddf0302))
* **main:** release 2.0.0 ([5dae4fc](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/5dae4fcaa83aa48489585f3370009d9f7fe460c1))
* **main:** release 2.0.1 ([28d69b3](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/28d69b38943dbd0321e681edfeced60ac11dd3db))
* **main:** release 2.0.1 ([c6a0c8b](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/c6a0c8bc750386cf86e6f27d937083957b6c3b06))
* **main:** release 2.1.0 ([c12b220](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/c12b220889af38ce564dc8dbcf5d0b9bec73c112))
* **main:** release 2.1.0 ([c094126](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/c09412615a308ec154c44e0256488dd2b36abc05))
* **main:** release 2.1.1 ([4774f55](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/4774f555d8797b5abaad935f35bf5d78638586eb))
* **main:** release 2.1.1 ([44c3fc6](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/44c3fc6419384c65b37f1c16a6fae703dc15fb4c))
* **main:** release 2.1.2 ([e5fabf7](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/e5fabf74b510181b0ebc73c6573799e36d35f0dc))
* **main:** release 2.1.2 ([f54e8fa](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/f54e8fac4bf3ef1ed9aff5cbb8d1ed484d1ea09f))
* **main:** release 2.1.3 ([960ca0d](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/960ca0d585906c3ce7bee79323b07326d31d759e))
* **main:** release 2.1.3 ([f65445f](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/f65445fff2490fe7b30178d8e8d957d9cb172460))
* **main:** release 2.1.4 ([1b2b75b](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/1b2b75bbd990edf8379ecf48fae59a858d03fd8e))
* **main:** release 2.1.4 ([2cc49f3](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/2cc49f3597c949f2cf02046072c87f4a0006f04d))
* **main:** release 2.2.0 ([85a2695](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/85a2695b43c918b92442905175817089b56f7aff))
* **main:** release 2.2.0 ([7f9dad3](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/7f9dad319aa61d5a5c96d88c4d0aa8998225995d))
* **main:** release 2.2.1 ([49d729e](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/49d729e07dcb41a49a7366c514eb0d2fe8778100))
* **main:** release 2.2.1 ([e1b83d4](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/e1b83d4e7b9e69a248ee15fd7669889c82ef09ea))
* **main:** release 2.2.2 ([#61](https://github.com/mynaparrot/plugNmeet-sdk-php/issues/61)) ([e78d47d](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/e78d47d9701c02df4520efec0fd680fee1448cbb))

## [2.2.2](https://github.com/mynaparrot/plugNmeet-sdk-php/compare/v2.2.1...v2.2.2) (2026-07-08)


### Bug Fixes

* bump proto ([bc6654d](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/bc6654d109f62a95df40e4a01b652bf17ec913e7))
* **deps:** update dependency firebase/php-jwt to v7.1.0 ([fe121df](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/fe121df79199927aed857a83e8947094e94c445e))
* **deps:** update dependency firebase/php-jwt to v7.1.0 ([898e89e](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/898e89e49435cc6c41637f3f47194f61d3cfe95c))
* **deps:** update dependency google/protobuf to v5.35.1 ([af1f6a1](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/af1f6a18ab16676a55379b078c686e825e208b2e))
* **deps:** update dependency google/protobuf to v5.35.1 ([44ded62](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/44ded62f8ffc1a4336af016e641615ba4da0dcc6))
* **deps:** update dependency guzzlehttp/guzzle to v7.12.1 [security] ([45db074](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/45db074899c8c1545c6f7c5726c5b7a3c8bd2621))
* **deps:** update dependency guzzlehttp/guzzle to v7.12.1 [security] ([6fa0d2f](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/6fa0d2f86f2085349db08253586cd94889e3ee38))
* **deps:** update dependency guzzlehttp/guzzle to v7.12.3 ([eb12a61](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/eb12a61293ecb6eb33839bb097b6f974a3a690d9))
* **deps:** update dependency guzzlehttp/guzzle to v7.12.3 ([193c48b](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/193c48bf42d8f8225c55a2218609920e5afc6b91))
* **deps:** update dependency guzzlehttp/guzzle to v7.13.2 ([1b5e320](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/1b5e32003e2e200faba7b639127db511210f380a))
* **deps:** update dependency guzzlehttp/guzzle to v7.13.2 ([984c7fa](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/984c7fa20b3d77ca7196fda5e125a7fad6c81d43))
* **deps:** update dependency guzzlehttp/guzzle to v7.13.3 ([4803fc1](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/4803fc10c4d98f19ebcf64848fedacf820df1d6c))
* **deps:** update dependency guzzlehttp/guzzle to v7.13.3 ([0e63e3d](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/0e63e3d198e20b72c0d6c202f001bd9e2971eb0c))
* **deps:** update dependency ramsey/uuid to v4.9.3 ([652194d](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/652194d78027e9e1204f137ef3babad0080f0a83))
* **deps:** update dependency ramsey/uuid to v4.9.3 ([8e702fd](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/8e702fd391db56e5d3a4719c5a287deab8b4c89c))
* exclude `composer.lock` from archive ([f139ec3](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/f139ec3e8a526678b1899103f05dedb0f8d1a9cc))


### Miscellaneous Chores

* **deps:** update actions/checkout action to v7 ([993ce9a](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/993ce9ad6e4e3e9a14001af49d29ad6979475a81))
* **deps:** update actions/checkout action to v7 ([61f2e3b](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/61f2e3bd10f482d3554ff26deb13e6aedf8f6ff8))

## [2.2.1](https://github.com/mynaparrot/plugNmeet-sdk-php/compare/v2.2.0...v2.2.1) (2026-06-04)


### Bug Fixes

* added `.gitattributes` file ([25bac4c](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/25bac4c7b43a54c4bf05e4fa42362be1cfc385da))
* **deps:** update dependency guzzlehttp/guzzle to v7.11.0 ([66ec0be](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/66ec0be43d0b0af938c5cef8cc92394aaa1a62c2))
* **deps:** update dependency guzzlehttp/guzzle to v7.11.0 ([20a2fdd](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/20a2fdd56032bf570fe6a5655cd3586714df20f8))
* don't include other files ([df10626](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/df1062691a9fb787986057b7c5313fb455d1ab0c))


### Miscellaneous Chores

* **deps:** update dependency overtrue/phplint to v9.7.2 ([17092b1](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/17092b1a1747380845bf2ff7461c6bb1fb955334))
* **deps:** update dependency overtrue/phplint to v9.7.2 ([8a6263a](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/8a6263a11703947397ede8b771af919b9677436b))

## [2.2.0](https://github.com/mynaparrot/plugNmeet-sdk-php/compare/v2.1.4...v2.2.0) (2026-05-30)


### Features

* added new `broadcastToRoom` and `uploadWhiteboardFile` endpoint ([b0cedf3](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/b0cedf35706e20c71190565dd07447794420645c))
* **API:** added new API endpoint `mergeRecordings` ([311a7e0](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/311a7e00270cb907cf493a96b0f58ae29da38dd5))


### Bug Fixes

* bump proto ([9bcad14](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/9bcad14aad161066ba01f00252eaa9f02bd39cae))
* bump proto ([fb874ad](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/fb874ade56b2a57d5d2a9208e1faf045bedf5b95))
* bump proto ([fe69010](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/fe69010510eca012dd5bddb540a4c49125f47fda))
* **deps:** update dependency google/protobuf to v5.35.0 ([cbb1653](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/cbb16535ec93ad7efbf0d6d95f225162eca5ccd8))
* **deps:** update dependency google/protobuf to v5.35.0 ([cae3a89](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/cae3a8906f379f69dd058096fa4a4954031dde7c))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.1 ([11cfbd3](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/11cfbd3edd30c46cb417a54aae190d641eaca2f7))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.1 ([0b4c13b](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/0b4c13bfa4121f5f3ea4dc1509d0ca6d19032869))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.2 ([58fd8d2](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/58fd8d2c0d9838657e0aa6e41a296c541eabf1c2))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.2 ([ca8cedf](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/ca8cedfced99d6c5d5333ec11fb15d956ad87ac1))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.3 ([3e3d077](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/3e3d07774e0e5aa076006a149e84872a4ab6988b))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.3 ([c815a51](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/c815a51ab695311dfbc8cce7d15bd7eddf1516c2))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.4 ([621b91e](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/621b91e908c21cb9e2ec7788cdcd8c8ebeede514))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.4 ([30cd0b4](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/30cd0b44395e91651096fdca86ba2affbc313157))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.5 ([4513159](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/45131594b938ec2408cad077698557fe288c9e28))
* **deps:** update dependency guzzlehttp/guzzle to v7.10.5 ([914c43c](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/914c43c5fae7c1a67cce206ad8c7a43050707efa))
* handle error message better way ([f321855](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/f321855a509c29845232ce6ca036159193fb98f8))
* handle network error related messages better way ([adc33ac](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/adc33ac80c04ea83f901731f51fc7eeda1ab5448))
* just path the path and client to decide what to do ([2e3e6dd](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/2e3e6dd338c3cd054f0c0e77dc9aeeca784b04a4))

## [2.1.4](https://github.com/mynaparrot/plugNmeet-sdk-php/compare/v2.1.3...v2.1.4) (2026-05-12)


### Bug Fixes

* added helper class to simplify/formate analytics data ([8f29d3d](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/8f29d3d5e82bab838a731cd1134da1c6642816f6))
* added more clear docs ([8ba79cf](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/8ba79cf6ae6c2e09dfe5929548ef145d6e3a8e05))
* lint ([2529fc0](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/2529fc0c6d05ba22cf524984f8559c95be4a2d90))
* lint ([5335f56](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/5335f5666441b381c90209524f2cadad1c40e074))


### Miscellaneous Chores

* **deps:** update googleapis/release-please-action action to v5 ([df22872](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/df22872ff4bb685b5d157505cfefe780486bd7df))
* **deps:** update googleapis/release-please-action action to v5 ([396665b](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/396665b95bd1f80981705c608480b223d8b08b03))

## [2.1.3](https://github.com/mynaparrot/plugNmeet-sdk-php/compare/v2.1.2...v2.1.3) (2026-04-21)


### Bug Fixes

* bump proto ([5764cf2](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/5764cf2631657e0662d113244bf9217e22033b64))

## [2.1.2](https://github.com/mynaparrot/plugNmeet-sdk-php/compare/v2.1.1...v2.1.2) (2026-04-20)


### Bug Fixes

* allow to set custom HttpClient ([da72cb6](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/da72cb6e14401f2e62283fa5882e4268f8de0a40))

## [2.1.1](https://github.com/mynaparrot/plugNmeet-sdk-php/compare/v2.1.0...v2.1.1) (2026-04-12)


### Bug Fixes

* bump proto ([42177f1](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/42177f19570ca23b4a25b90227527a39ccb75733))
* bump proto ([5e9eb7f](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/5e9eb7f70d02e2941b657ea4eff045d28c41728b))
* bump proto ([2b64600](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/2b646002da2faa68db56fc5319671a2ad073f6f4))
* **deps:** update dependency firebase/php-jwt to v7.0.3 ([3473b34](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/3473b3428ba06c2ae2bee704747c12bcd9bc0ac6))
* **deps:** update dependency firebase/php-jwt to v7.0.3 ([c457267](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/c4572674799c2c2aefbfa3f871149c907c28762a))
* **deps:** update dependency firebase/php-jwt to v7.0.5 ([521ef57](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/521ef5751ab83f127bc35d67a91cff9d18db2f70))
* **deps:** update dependency firebase/php-jwt to v7.0.5 ([b8b4437](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/b8b4437a7a8a863de11ff6377929aead18d3f1ac))
* **deps:** update dependency google/protobuf to v4.33.5 ([31fde1d](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/31fde1dc3559f013a99c55cf6ce12e5608c81fed))
* **deps:** update dependency google/protobuf to v4.33.5 ([093de05](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/093de05150b29690e0e8c68345bf1133e2d3c1bc))
* **deps:** update dependency google/protobuf to v4.33.6 [security] ([f912017](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/f91201757acb51f1d5d9d03799fefec9085d3daa))
* **deps:** update dependency google/protobuf to v4.33.6 [security] ([b45be5f](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/b45be5f99fd3e3e35dff31860f2a6677537e07b6))
* **deps:** update dependency google/protobuf to v5 ([727132c](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/727132c67cd3bf2601ce149a909576da081e84be))
* **deps:** update dependency google/protobuf to v5 ([84767a9](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/84767a994c44304d73ec578f136b7f478147d974))

## [2.1.0](https://github.com/mynaparrot/plugNmeet-sdk-php/compare/v2.0.1...v2.1.0) (2026-01-21)


### Features

* SIP/VoIP dial in ([8625d73](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/8625d732b2f8da58275ea321cd07d402b709fafb))


### Bug Fixes

* bump deps ([25dafa3](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/25dafa3704ff5a1350786633f7de831a802afc0f))
* **deps:** update dependency google/protobuf to v4.33.4 ([f274542](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/f2745428e5777b30e722224fe7c09f7ee97b380d))
* **deps:** update dependency google/protobuf to v4.33.4 ([4240c2c](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/4240c2c90a2476fa4a7394de71757cb6de45dc59))

## [2.0.1](https://github.com/mynaparrot/plugNmeet-sdk-php/compare/v2.0.0...v2.0.1) (2026-01-09)


### Bug Fixes

* to use `staticAssetsPath` CDN host ([58110df](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/58110dfe1a4dc1b9cf252746e22ce970fb285b70))

## [2.0.0](https://github.com/mynaparrot/plugNmeet-sdk-php/compare/v1.6.2...v2.0.0) (2025-12-20)


### ⚠ BREAKING CHANGES

* project migrated to use `Protocol buffers`

### Features

* artifacts API ([486a209](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/486a2090256d8652f13d21a32e29a64504a177a6))
* project migrated to use `Protocol buffers` ([2fcc4d8](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/2fcc4d85da570cc3a1bea9f76240e7d296b0854a))
* recording metadata update ([f89d275](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/f89d275e892a346192b420068b4f8abdd9947ec7))


### Bug Fixes

* added new option `disableDarkMode` & `right_panel_bg_color` ([bdd30d5](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/bdd30d50414d363af2eea6a97007f04e7a65cc87))
* added new options ([2537d78](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/2537d78ea60752b6fa127c9b7c7a1d9d9ba94d01))
* bump proto ([472f007](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/472f00704f9c3c782ab3ca6155cbff0ba9eadeb0))
* bump proto ([738238c](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/738238c45eb7a3f94a4629417fd83986f9bc6740))
* bump proto ([ca7a9ac](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/ca7a9ac72a46c03c8424816bb05318451bda8fc4))
* bump protocol ([79e6157](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/79e615792087a9079014e33e3d1c8caa72e4396b))
* converting JSON values to correct type ([7622ecf](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/7622ecfb06d50b1a378f4317ea582458d1e8008f))
* **deps:** update dependency google/protobuf to v4.33.2 ([1eafcab](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/1eafcab6f7cb03f042e29e16db606c923ea2fc3a))
* **deps:** update dependency google/protobuf to v4.33.2 ([38ea2af](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/38ea2af8403c0b27026f6a79af2afb7818bc552f))
* **refactor:** Builds a Protobuf message object from array directly ([f15697a](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/f15697afc9fced41adb1a5d9acdb9190cf46a263))
* **refactor:** use Protobuf message's setters and getters to handle type conversions ([dc0235e](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/dc0235e473e3d6a02b268bb05711ccd993bf1b0a))

## [1.6.2](https://github.com/mynaparrot/plugNmeet-sdk-php/compare/v1.6.1...v1.6.2) (2025-10-15)


### Bug Fixes

* better error handling ([74b7b70](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/74b7b70b56fa1e19178f1a8039d14aa38f184ce2))
* lint ([e55e6ca](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/e55e6ca7d7317385613c5665d48a954d80da0e66))
* lint ([1603c91](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/1603c91730b2ffc39c24c33e050411891f5e072d))
* set default `$timeout` to 60 seconds and configurable ([bbc0ff8](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/bbc0ff85011a0a8c4879a1f688da9660bfbe0ca3))

## [1.6.1](https://github.com/mynaparrot/plugNmeet-sdk-php/compare/v1.6.0...v1.6.1) (2025-10-15)


### Bug Fixes

* **ci:** try to fix build ([4d81a49](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/4d81a4966481262d736fac88f43393b39c0200b0))
* **deps:** update dependency firebase/php-jwt to v6.11.0 ([e5a3697](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/e5a3697e3305585341ebe37bc1f425978ab08879))
* **deps:** update dependency firebase/php-jwt to v6.11.0 ([9cbad82](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/9cbad828a6256e7401f8acdbd9363354841f4fb3))
* **deps:** update dependency firebase/php-jwt to v6.11.1 ([d8cd979](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/d8cd979deff62664fc5ac3e68dcb5fd471b5f552))
* **deps:** update dependency firebase/php-jwt to v6.11.1 ([0c46095](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/0c46095e69feea58b42e0afa429a5ab5ac101f56))
* link update ([5047809](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/50478093be1eeeb92d36509391118a6ffb51a15c))
* lint ([53535d1](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/53535d15ae272c3f0624b11bb85a75bfff27c6b7))
* migrate project to use `GuzzleHttp` library ([236dfce](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/236dfce3e503b779a9872bfe17238fd32c9ea97f))
* prep for new client ([293d53e](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/293d53e075e581d7a06328ebc33d77d321c202fd))
* prep for new client ([e4bab4c](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/e4bab4cff6ec4dc947863be3477ce7e310d49585))
* update deps ([309848d](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/309848ddac9b8352da8405fa0149afc41e012291))


### Miscellaneous Chores

* **deps:** update actions/checkout action to v5 ([61421b1](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/61421b1e06f68def63bcec6c9431ec8703ebf04c))
* **deps:** update actions/checkout action to v5 ([a6a4196](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/a6a4196d21521a5fbbffa6a1503469a7abbf2718))
* **deps:** update dependency overtrue/phplint to v9.5.5 ([47c3ec7](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/47c3ec78e5c945added9eee4593fbf2c382ec9f1))
* **deps:** update dependency overtrue/phplint to v9.5.5 ([52dc313](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/52dc3134eef5ffea87382c2fa2ac26ffb7c7813c))
* **deps:** update dependency overtrue/phplint to v9.5.6 ([30fd598](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/30fd598752eda80398b8476fcd1fb2392717a9c9))
* **deps:** update dependency overtrue/phplint to v9.5.6 ([1bf4d08](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/1bf4d08e3f597f8fe07872f09f74813bd135b82d))
* **deps:** update dependency squizlabs/php_codesniffer to v3.11.2 ([3df31ed](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/3df31ed4cea643953904698ef4acd189e9153941))
* **deps:** update dependency squizlabs/php_codesniffer to v3.11.2 ([13d9505](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/13d9505f954656f063909ca620e8d113ae24664f))
* **deps:** update dependency squizlabs/php_codesniffer to v3.11.3 ([909151b](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/909151b7459fc04a228ae8a968177c4075455318))
* **deps:** update dependency squizlabs/php_codesniffer to v3.11.3 ([1f42e38](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/1f42e38c9a94ea2ac98ec9e40ea1939c08014e3a))
* **deps:** update peaceiris/actions-gh-pages action to v4 ([6ead7f9](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/6ead7f95e1334be8c033af90bd344451ad995265))
* **deps:** update peaceiris/actions-gh-pages action to v4 ([3504eb5](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/3504eb50416a0d96a1a012b5f99caa0b392e67d6))

## [1.6.0](https://github.com/mynaparrot/plugNmeet-sdk-php/compare/v1.5.2...v1.6.0) (2024-11-26)


### Features

* dropped support for PHP 7.4 ([d09739c](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/d09739cd47fd9c2248298fd62e7d5146ab9f97d3))


### Bug Fixes

* **ci:** added release-please-action ([4498a14](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/4498a147ba54bb911acfd25951d945276d61ebde))
* **ci:** update PHP version ([7000cea](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/7000cea81401596f8299d575c5f62baf0b9f4596))


### Miscellaneous Chores

* **deps:** update actions/checkout action to v4 ([c754b50](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/c754b50b01b3b09a8a8c4413fe444c93b9a99ff3))
* **deps:** update dependency squizlabs/php_codesniffer to v3.11.1 ([18f64c9](https://github.com/mynaparrot/plugNmeet-sdk-php/commit/18f64c993503edbf882085d671ca899020460714))
