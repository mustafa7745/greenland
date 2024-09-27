<?php
namespace Manager;

require_once "../../app/delivery_men/index.php";


class ThisClass
{
    // use \AppsPostData;
    public DeliveryMen $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new DeliveryMen();
    }


    function main(): string
    {
        if (getTag() == "read") {
            return $this->read();
        } elseif (getTag() == "search") {
            return $this->search();
        } elseif (getTag() == "readById") {
            return $this->readById();
        } elseif (getTag() == "getAmountNotcompleteOrders") {
            return $this->getAmountNotcompleteOrders();
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
    private function search(): string
    {
        $resultData = $this->controller->search();
        return json_encode($resultData);
    }
    private function readById(): string
    {
        $resultData = $this->controller->readById();
        return json_encode($resultData);
    }
    private function getAmountNotcompleteOrders(): string
    {
        $resultData = $this->controller->getAmountNotcompleteOrders();
        return json_encode($resultData);
    }
}

$this_class = new ThisClass();
die($this_class->main());
