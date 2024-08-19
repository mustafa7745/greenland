<?php
namespace Manager;

require_once "../../app/orders/index.php";


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
        } elseif (getTag() == "updateSystemOrderNumber") {
            return $this->updateSystemOrderNumber();
        } elseif (getTag() == "readOrderStatus") {
            return $this->readOrderStatus();
        } elseif (getTag() == "add") {
            return $this->add();
        } elseif (getTag() == "addProductToOrder") {
            return $this->addProductToOrder();
        } elseif (getTag() == "search") {
            return $this->search();
        } elseif (getTag() == "updateQuantity") {
            return $this->updateQuantity();
        } elseif (getTag() == "cencelOrder") {
            return $this->cencelOrder();
        } elseif (getTag() == "readOrdersOfUsers") {
            return $this->readOrdersOfUsers();
        } elseif (getTag() == "delete") {
            return $this->delete();
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
    private function updateSystemOrderNumber(): string
    {
        $resultData = $this->controller->updateSystemOrderNumber();
        return json_encode($resultData);
    }

    private function readOrderProducts(): string
    {
        $resultData = $this->controller->readOrderProducts();
        return json_encode($resultData);
    }
    private function readOrderreadUncollectedOrdersProducts(): string
    {
        $resultData = $this->controller->readUncollectedOrders();
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
    private function addProductToOrder(): string
    {
        $resultData = $this->controller->addProductToOrder();
        return json_encode($resultData);
    }

    private function search(): string
    {
        $resultData = $this->controller->search();
        return json_encode($resultData);
    }
    private function delete(): string
    {
        $resultData = $this->controller->delete();
        return json_encode($resultData);
    }
    // 
    private function updateQuantity(): string
    {
        $resultData = $this->controller->updateQuantity();
        return json_encode($resultData);
    }
    private function cencelOrder(): string
    {
        $resultData = $this->controller->cencelOrder();
        return json_encode($resultData);
    }
    private function readOrdersOfUsers(): string
    {
        $resultData = $this->controller->readOrdersOfUsers();
        return json_encode($resultData);
    }
}

$this_class = new ThisClass();
die($this_class->main());
