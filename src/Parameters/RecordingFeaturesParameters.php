<?php

namespace Mynaparrot\Plugnmeet\Parameters;

/**
 *
 */
class RecordingFeaturesParameters
{
    /**
     * @var bool
     */
    protected $isAllow = true;
    /**
     * @var bool
     */
    protected $isAllowCloud = true;
    /**
     * @var bool
     */
    protected $isAllowLocal = true;
    /**
     * @var bool
     */
    protected $enableAutoCloudRecording = false;

    /**
     * @var bool
     */
    protected $onlyRecordAdminWebcams = false;

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @return bool
     */
    public function isAllow(): bool
    {
        return $this->isAllow;
    }

    /**
     * @param bool $isAllow
     */
    public function setIsAllow(bool $isAllow): void
    {
        $this->isAllow = filter_var($isAllow, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isAllowCloud(): bool
    {
        return $this->isAllowCloud;
    }

    /**
     * @param bool $isAllowCloud
     */
    public function setIsAllowCloud(bool $isAllowCloud): void
    {
        $this->isAllowCloud = filter_var($isAllowCloud, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isAllowLocal(): bool
    {
        return $this->isAllowLocal;
    }

    /**
     * @param bool $isAllowLocal
     */
    public function setIsAllowLocal(bool $isAllowLocal): void
    {
        $this->isAllowLocal = filter_var($isAllowLocal, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isEnableAutoCloudRecording(): bool
    {
        return $this->enableAutoCloudRecording;
    }

    /**
     * @param bool $enableAutoCloudRecording
     */
    public function setEnableAutoCloudRecording(bool $enableAutoCloudRecording): void
    {
        $this->enableAutoCloudRecording = filter_var($enableAutoCloudRecording, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return bool
     */
    public function isOnlyRecordAdminWebcams(): bool
    {
        return $this->onlyRecordAdminWebcams;
    }

    /**
     * @param bool $onlyRecordAdminWebcams
     */
    public function setOnlyRecordAdminWebcams(bool $onlyRecordAdminWebcams): void
    {
        $this->onlyRecordAdminWebcams = filter_var($onlyRecordAdminWebcams, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * @return array
     */
    public function buildBody(): array
    {
        return array(
            "is_allow" => $this->isAllow(),
            "is_allow_cloud" => $this->isAllowCloud(),
            "is_allow_local" => $this->isAllowLocal(),
            "enable_auto_cloud_recording" => $this->isEnableAutoCloudRecording(),
            "only_record_admin_webcams" => $this->isOnlyRecordAdminWebcams()
        );
    }
}
