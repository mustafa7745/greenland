<?php
namespace DeliveryMen;

require_once __DIR__ . "/../../app/acceptance/index.php";


class ThisClass
{
    // use \AppsPostData;
    public Acceptance $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new Acceptance();
    }


    function main(): string
    {
        if (getTag() == "reject") {
            return $this->reject();
        } elseif (getTag() == "accept") {
            return $this->accept();
        } elseif (getTag() == "add") {
            return $this->add();
        } else {
            UNKOWN_TAG();
        }
    }
    //Main Functin CRUD
    private function reject(): string
    {
        $resultData = $this->controller->reject();
        return json_encode($resultData);

    }
    private function add(): string
    {
        $resultData = $this->controller->add();
        return json_encode($resultData);

    }
    private function accept(): string
    {
        $resultData = $this->controller->accept();
        return json_encode($resultData);

    }
}

$this_class = new ThisClass();
die($this_class->main());
