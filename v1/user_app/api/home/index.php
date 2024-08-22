<?php
namespace UserApp;

require_once __DIR__ . "/../../app/home/index.php";


class ThisClass
{
    // use \AppsPostData;
    public Home $controller;
    // 

    function __construct()
    {
        $this->controller = new Home();
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
