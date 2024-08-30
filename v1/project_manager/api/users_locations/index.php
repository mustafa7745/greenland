<?php
namespace Manager;

require_once "../../app/users_locations/index.php";


class ThisClass
{
    // use \AppsPostData;
    public UsersLocations $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new UsersLocations();
    }


    function main(): string
    {
        if (getTag() == "read") {
            return $this->read();
        } elseif (getTag() == "add") {
            return $this->add();
        } elseif (getTag() == "updateStreet") {
            return $this->updateStreet();
        } elseif (getTag() == "updateNearTo") {
            return $this->updateNearTo();
        } elseif (getTag() == "updateContactPhone") {
            return $this->updateContactPhone();
        } elseif (getTag() == "updateLatLong") {
            return $this->updateLatLong();
        } elseif (getTag() == "updateUrl") {
            return $this->updateUrl();
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
    private function add(): string
    {
        $resultData = $this->controller->add();
        return json_encode($resultData);

    }
    private function updateUrl(): string
    {
        $resultData = $this->controller->updateUrl();
        return json_encode($resultData);

    }
    private function updateLatLong(): string
    {
        $resultData = $this->controller->updateLatLong();
        return json_encode($resultData);

    }
    private function updateContactPhone(): string
    {
        $resultData = $this->controller->updateContactPhone();
        return json_encode($resultData);

    }
    private function updateNearTo(): string
    {
        $resultData = $this->controller->updateNearTo();
        return json_encode($resultData);

    }
    private function updateStreet(): string
    {
        $resultData = $this->controller->updateStreet();
        return json_encode($resultData);

    }
}

$this_class = new ThisClass();
die($this_class->main());
