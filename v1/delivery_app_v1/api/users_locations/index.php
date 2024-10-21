<?php
namespace DeliveryMen;

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
