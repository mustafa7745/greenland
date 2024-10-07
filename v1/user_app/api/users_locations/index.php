<?php

namespace UserApp;

require_once __DIR__ . "/../../app/users_locations/index.php";


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
        } elseif (getTag() == "readDeliveryPrice") {
            return $this->readDeliveryPrice();
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
    private function readDeliveryPrice(): string
    {
        $resultData = $this->controller->readDeliveryPrice();
        return json_encode($resultData);

    }
    private function add(): string
    {
        $resultData = $this->controller->add();
        return json_encode($resultData);

    }
}

$this_class = new ThisClass();
die($this_class->main());
