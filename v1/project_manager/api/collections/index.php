<?php
namespace Manager;

require_once "../../app/collections/index.php";


class ThisClass
{
    // use \AppsPostData;
    public Collections $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new Collections();
    }


    function main(): string
    {
        if (getTag() == "read") {
            return $this->read();
        }elseif (getTag() == "collect") {
            return $this->collect();
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
    private function collect(): string
    {
        $resultData = $this->controller->collect();
        return json_encode($resultData);
    }
}

$this_class = new ThisClass();
die($this_class->main());
