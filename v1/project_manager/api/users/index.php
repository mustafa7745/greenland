<?php
namespace Manager;

require_once "../../app/users/index.php";


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
        if (getTag() == "read") {
            return $this->read();
        }elseif (getTag() == "readById") {
            return $this->readById();
        } elseif (getTag() == "add") {
            return $this->add();
        } elseif (getTag() == "updateName") {
            return $this->updateName();
        } elseif (getTag() == "updatePassword") {
            return $this->updatePassword();
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
    private function readById(): string
    {
        $resultData = $this->controller->readById();
        return json_encode($resultData);
    }
    private function add(): string
    {
        $resultData = $this->controller->add();
        return json_encode($resultData);
    }
    private function updateName(): string
    {
        $resultData = $this->controller->updateName();
        return json_encode($resultData);
    }
    private function updatePassword(): string
    {
        $resultData = $this->controller->updatePassword();
        return json_encode($resultData);
    }
}

$this_class = new ThisClass();
die($this_class->main());
