<?php
namespace Manager;

require_once "../../app/products/index.php";


class ThisClass
{
    // use \AppsPostData;
    public Products $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new Products();
    }


    function main(): string
    {
        if (getTag() == "readAll") {
            return $this->readAll();
        } elseif (getTag() == "search") {
            return $this->search();
        } else {
            UNKOWN_TAG();
        }
    }
    //Main Functin CRUD
   
    private function readAll(): string
    {
        $resultData = $this->controller->readAll();
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
