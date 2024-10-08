<?php
namespace SU1;

require_once "../../app/products_groups/index.php";


class ThisClass
{
    // use \AppsPostData;
    public ProductsGroups $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new ProductsGroups();
    }


    function main(): string
    {
        if (getTag() == "read") {
            return $this->read();
        } elseif (getTag() == "add") {
            return $this->add();
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
}

$this_class = new ThisClass();
die($this_class->main());
