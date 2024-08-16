<?php
namespace SU1;

require_once ("../../../middleware.php");
require_once (getPath() . "app/su1/level_permissions/index.php");

class ThisClass
{

    use \SharedPostData;
    use \DevicesPostData;
    public LevelPermissions $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new LevelPermissions();
    }


    function main(): string
    {
        if ($this->getTag() == "read") {
            return $this->read();
        }  else {
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
