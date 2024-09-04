<?php
namespace SU1;

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
        if (getTag() == "search") {
            return $this->search();
        } elseif (getTag() == "add") {
            return $this->add();
        } else {
            UNKOWN_TAG();
        }
    }
    //Main Functin CRUD

    private function add(): string
    {
        $resultData = $this->controller->add();
        return json_encode($resultData);

    }

    private function search(): string
    {

        $resultData = $this->controller->search();
        return json_encode($resultData);
    }
}

$this_class = new ThisClass();
die($this_class->main());
