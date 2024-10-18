<?php
class ModelPostData1
{
    public $deviceId;
    public $deviceInfo;
    public $appDeviceToken;
    public $appSha;
    // public $devicePublicKey;
    public $packageName;
    public $appVersion;
    public function __construct($deviceId, $deviceInfo, $appDeviceToken, $appSha, $packageName, $appVersion)
    {
        $this->deviceId = $deviceId;
        $this->deviceInfo = $deviceInfo;
        $this->appDeviceToken = $appDeviceToken;
        $this->appSha = $appSha;
        // $this->devicePublicKey = $devicePublicKey;
        $this->packageName = $packageName;
        $this->appVersion = $appVersion;
    }
}