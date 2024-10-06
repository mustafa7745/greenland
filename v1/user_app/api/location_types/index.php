<?php

namespace UserApp;

require_once __DIR__ . "/../../app/location_types/index.php";


class ThisClass
{
    // use \AppsPostData;
    public LocationTypes $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new LocationTypes();
    }


    function main(): string
    {
        if (getTag() == "read") {
            return $this->read();
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
}

$this_class = new ThisClass();
die($this_class->main());
