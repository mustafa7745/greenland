<?php
namespace SU1;

require_once "../../app/products/index.php";


class ThisClass
{
    // use \AppsPostData;
    public Products $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new Products();
    }


    function main(): string
    {
        if (getTag() == "read") {
            return $this->read();
        } elseif (getTag() == "add") {
            return $this->add();
        }
        elseif (getTag() == "addWithoutImage") {
            return $this->addWithoutImage();
        }

        elseif (getTag() == "search") {
            return $this->search();
        } elseif (getTag() == "updateName") {
            return $this->updateName();
        } elseif (getTag() == "updateAvailable") {
            return $this->updateAvailable();
        } elseif (getTag() == "updateNumber") {
            return $this->updateNumber();
        } elseif (getTag() == "updateGroup") {
            return $this->updateGroup();
        } elseif (getTag() == "updatePostPrice") {
            return $this->updatePostPrice();
        } elseif (getTag() == "updateOrder") {
            return $this->updateOrder();
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
    private function add(): string
    {
        $resultData = $this->controller->add();
        return json_encode($resultData);

    }

     private function addWithoutImage(): string
    {
        $resultData = $this->controller->addWithoutImage();
        return json_encode($resultData);

    }

    private function search(): string
    {

        $resultData = $this->controller->search();
        return json_encode($resultData);
    }
    // 
    private function updateName(): string
    {
        $resultData = $this->controller->updateName();
        return json_encode($resultData);
    }
    private function updateAvailable(): string
    {
        $resultData = $this->controller->updateAvailable();
        return json_encode($resultData);
    }
    private function updateNumber(): string
    {
        $resultData = $this->controller->updateNumber();
        return json_encode($resultData);
    }
    private function updateOrder(): string
    {
        $resultData = $this->controller->updateOrder();
        return json_encode($resultData);
    }
    private function updatePostPrice(): string
    {
        $resultData = $this->controller->updatePostPrice();
        return json_encode($resultData);
    }
    private function updateGroup(): string
    {
        $resultData = $this->controller->updateGroup();
        return json_encode($resultData);
    }
    private function delete(): string
    {
        $resultData = $this->controller->delete();
        return json_encode($resultData);
    }
}

$this_class = new ThisClass();
die($this_class->main());
