<?php
namespace UserApp;

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
        if (getTag()  == "read") {
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
