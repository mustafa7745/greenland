<?php
namespace UserApp;

require_once __DIR__ . "/../../app/orders/index.php";


class ThisClass
{
    // use \AppsPostData;
    public Orders $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new Orders();
    }


    function main(): string
    {
        if (getTag() == "read") {
            return $this->read();
        } elseif (getTag() == "readOrderProducts") {
            return $this->readOrderProducts();
        } elseif (getTag() == "readOrderStatus") {
            return $this->readOrderStatus();
        } elseif (getTag() == "add") {
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
    private function readOrderProducts(): string
    {
        $resultData = $this->controller->readOrderProducts();
        return json_encode($resultData);

    }
    private function readOrderStatus(): string
    {
        $resultData = $this->controller->readOrderStatus();
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
