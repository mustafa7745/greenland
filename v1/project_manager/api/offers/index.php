<?php
namespace Manager;

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
        if (getTag() == "search") {
            return $this->search();
        } else {
            UNKOWN_TAG();
        }
    }
    //Main Functin CRUD

    private function search(): string
    {
        $resultData = $this->controller->search();
        return json_encode($resultData);
    }
}

$this_class = new ThisClass();
die($this_class->main());
