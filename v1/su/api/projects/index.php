<?php
namespace SU1;

require_once "../../app/projects/index.php";


class ThisClass
{
    // use \AppsPostData;
    public Projects $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new Projects();
    }


    function main(): string
    {
        if (getTag() == "read") {
            return $this->read();
        } elseif (getTag() == "updateDeliveryPrice") {
            return $this->updateDeliveryPrice();
        } elseif (getTag() == "updateLatLong") {
            return $this->updateLatLong();
        } elseif (getTag() == "updatePassword") {
            return $this->updatePassword();
        } elseif (getTag() == "updateRequestOrderStatus") {
            return $this->updateRequestOrderStatus();
        } elseif (getTag() == "updateRequestOrderMessage") {
            return $this->updateRequestOrderMessage();
        } elseif (getTag() == "updateDeviceId") {
            return $this->updateDeviceId();
        } else {
            UNKOWN_TAG();
        }
    }
    //Main Functin CRUD
    private function read(): string
    {
        $resultData = $this->controller->read();
        return json_encode($resultData);
    }
    private function updateDeliveryPrice(): string
    {
        $resultData = $this->controller->updateDeliveryPrice();
        return json_encode($resultData);
    }
    private function updateLatLong(): string
    {
        $resultData = $this->controller->updateLatLong();
        return json_encode($resultData);
    }
    private function updatePassword(): string
    {
        $resultData = $this->controller->updatePassword();
        return json_encode($resultData);
    }
    private function updateDeviceId(): string
    {
        $resultData = $this->controller->updateDeviceId();
        return json_encode($resultData);
    }
    private function updateRequestOrderMessage(): string
    {
        $resultData = $this->controller->updateRequestOrderMessage();
        return json_encode($resultData);
    }
    private function updateRequestOrderStatus(): string
    {
        $resultData = $this->controller->updateUpdateRequestOrderStatus();
        return json_encode($resultData);
    }
}

$this_class = new ThisClass();
$result = $this_class->main();
getDB()->conn->close();
die($result);
