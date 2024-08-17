<?php
namespace DeliveryMen;

require_once "../../app/orders/index.php";


class ThisClass
{
    public Orders $controller;
    // 
    function __construct()
    {
        //
        $this->controller = new Orders();
    }


    function main(): string
    {
        if (getTag() == "orderOnRoad") {
            return $this->orderOnRoad();
        } elseif (getTag() == "checkCode") {
            return $this->checkCode();
        } else {
            UNKOWN_TAG();
        }
    }
    //Main Functin CRUD

    private function orderOnRoad(): string
    {
        $resultData = $this->controller->orderOnRoad();
        return json_encode($resultData);

    }
    private function checkCode(): string
    {
        $resultData = $this->controller->checkCode();
        return json_encode($resultData);

    }

}

$this_class = new ThisClass();
die($this_class->main());
