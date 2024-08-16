<?php
require_once 'App.php';
require_once 'Device.php';
require_once 'DeviceSession.php';
require_once 'DeviceSessionIp.php';
class ModelRunApp
{
    public ModelApp $app;
    public ModelDevice $device;
    public ModelDeviceSession $deviceSession;
    public ModelDeviceSessionIp $deviceSessionIp;

    public function __construct($app, $device,$deviceSession,$deviceSessionIp)
    {
        $this->app = $app;
        $this->device = $device;
        $this->deviceSession = $deviceSession;
        $this->deviceSessionIp = $deviceSessionIp;
    }
}