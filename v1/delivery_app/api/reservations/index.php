<?php
namespace DeliveryMen;

require_once "../../app/reservations/index.php";


class ThisClass
{
    // use \AppsPostData;
    public Reservations $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new Reservations();
    }


    function main(): string
    {
        if (getTag()  == "read") {
            return $this->read();
        } elseif (getTag()  == "add") {
            return $this->add();
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
}

$this_class = new ThisClass();
die($this_class->main());
