<?php
namespace SU1;

require_once "../../app/offers/index.php";


class ThisClass
{
    // use \AppsPostData;
    public Offers $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new Offers();
    }


    function main(): string
    {
        if (getTag() == "read") {
            return $this->read();
        } elseif (getTag() == "add") {
            return $this->add();
        } elseif (getTag() == "updateName") {
            return $this->updateName();
        } elseif (getTag() == "updateDescription") {
            return $this->updateDescription();
        } elseif (getTag() == "updatePrice") {
            return $this->updatePrice();
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
    private function updateName(): string
    {
        $resultData = $this->controller->updateName();
        return json_encode($resultData);
    }
    private function updateDescription(): string
    {
        $resultData = $this->controller->updateDescription();
        return json_encode($resultData);
    }
    private function updatePrice(): string
    {
        $resultData = $this->controller->updatePrice();
        return json_encode($resultData);
    }
}

$this_class = new ThisClass();
die($this_class->main());
