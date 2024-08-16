<?php
namespace SU1;

require_once ("../../../middleware.php");
require_once (getPath() . "app/su1/devices_sessions/index.php");

class ThisClass
{

    use \SharedPostData;
    use \DevicesSessionsPostData;
    use \DevicesPostData;
    public DevicesSessions $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new DevicesSessions();
    }


    function main(): string
    {
        if ($this->getTag() == "readApps") {
            return $this->readApps();
        } elseif ($this->getTag() == "search") {
            return $this->search();
        } elseif ($this->getTag() == "updateName") {
            return $this->updateName();
        } elseif ($this->getTag() == "updateSha") {
            return $this->updateSha();
        } elseif ($this->getTag() == "updateVersion") {
            return $this->updateVersion();
        } else {
            UNKOWN_TAG();
        }
    }
    //Main Functin CRUD
    private function readApps(): string
    {
        $resultData = $this->controller->readApps($this->getInputDeviceId());
        return json_encode($resultData);

    }

    private function search(): string
    {

        $resultData = $this->controller->search($this->getSearch());
        return json_encode($resultData);
    }
    // 
    private function updateName(): string
    {
        $resultData = $this->controller->updateName($this->getInputAppId(), $this->getInputAppPackageName());
        return json_encode($resultData);
    }
    private function updateSha(): string
    {
        $resultData = $this->controller->updateSha($this->getInputAppId(), $this->getInputAppSha());
        return json_encode($resultData);
    }
    private function updateVersion(): string
    {
        $resultData = $this->controller->updateVersion($this->getInputAppId(), $this->getInputAppVersion());
        return json_encode($resultData);
    }
}

$this_class = new ThisClass();
die($this_class->main());
