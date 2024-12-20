<?php
namespace SU1;

require_once "../../app/categories/index.php";


class ThisClass
{
    // use \AppsPostData;
    public Categories $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new Categories();
    }


    function main(): string
    {
        if (getTag() == "read") {
            return $this->read();
        } elseif (getTag() == "add") {
            return $this->add();
        } elseif (getTag() == "search") {
            return $this->search();
        } elseif (getTag() == "updateName") {
            return $this->updateName();
        } elseif (getTag() == "updateImage") {
            return $this->updateImage();
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
    private function search(): string
    {
        $resultData = $this->controller->search();
        return json_encode($resultData);

    }
    private function add(): string
    {
        $resultData = $this->controller->add();
        return json_encode($resultData);

    }
    private function updateName(): string
    {
        $resultData = $this->controller->updateName();
        return json_encode($resultData);
    }
    private function updateImage(): string
    {
        $resultData = $this->controller->updateImage();
        return json_encode($resultData);
    }
    private function updateOrder(): string
    {
        $resultData = $this->controller->updateOrder();
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
