<?php
namespace SU1;

require_once "../../app/coupons/index.php";


class ThisClass
{
    // use \AppsPostData;
    public Coupons $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new Coupons();
    }


    function main(): string
    {
        if (getTag() == "read") {
            return $this->read();
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

}

$this_class = new ThisClass();
die($this_class->main());
