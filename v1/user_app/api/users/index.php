<?php

namespace UserApp;

require_once __DIR__ . "/../../app/users/index.php";


class ThisClass
{
    // use \AppsPostData;
    public Users $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new Users();
    }


    function main(): string
    {
        if (getTag() == "updateName") {
            return $this->updateName();
        } else {
            UNKOWN_TAG();
        }
    }
    //Main Functin CRUD
    private function updateName(): string
    {
        $resultData = $this->controller->updateName();
        return json_encode($resultData);
    }
}

$this_class = new ThisClass();
die($this_class->main());
