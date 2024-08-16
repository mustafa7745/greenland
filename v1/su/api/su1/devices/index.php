<?php
namespace SU1;

require_once ("../../../middleware.php");
require_once (getPath() . "app/su1/devices/index.php");

class ThisClass
{

    use \SharedPostData;
    use \DevicesPostData;
    public Devices $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new Devices();
    }


    function main(): string
    {
        if ($this->getTag() == "read") {
            return $this->read();
        } elseif ($this->getTag() == "search") {
            return $this->search();
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

    private function search(): string
    {

        $resultData = $this->controller->search($this->getSearch());
        return json_encode($resultData);
    }

}

$this_class = new ThisClass();
die($this_class->main());
