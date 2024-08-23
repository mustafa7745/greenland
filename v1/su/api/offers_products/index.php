<?php
namespace SU1;

require_once "../../app/offers_products/index.php";


class ThisClass
{
    // use \AppsPostData;
    public OffersProducts $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new OffersProducts();
    }


    function main(): string
    {
        if (getTag() == "read") {
            return $this->read();
        } elseif (getTag() == "add") {
            return $this->add();
        } elseif (getTag() == "updateQuantity") {
            return $this->updateQuantity();
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

    private function updateQuantity(): string
    {
        $resultData = $this->controller->updateQuantity();
        return json_encode($resultData);
    }
    // 
    private function delete(): string
    {
        $resultData = $this->controller->delete();
        return json_encode($resultData);
    }
}

$this_class = new ThisClass();
die($this_class->main());
