<?php
namespace Manager;

require_once "../../app/notifications/index.php";


class ThisClass
{
    // use \AppsPostData;
    public Notifications $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new Notifications();
    }


    function main(): string
    {
        if (getTag() == "add") {
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
}

$this_class = new ThisClass();
die($this_class->main());
