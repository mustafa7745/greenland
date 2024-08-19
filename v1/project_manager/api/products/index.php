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
        if (getTag() == "read") {
            return $this->read();
        } elseif (getTag() == "add") {
            return $this->add();
        } elseif (getTag() == "readAll") {
            return $this->readAll();
        } elseif (getTag() == "search") {
            return $this->search();
        } elseif (getTag() == "updateName") {
            return $this->updateName();
        } elseif (getTag() == "updateSha") {
            return $this->updateSha();
        } elseif (getTag() == "updateVersion") {
            return $this->updateVersion();
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
    private function readAll(): string
    {
        $resultData = $this->controller->readAll();
        return json_encode($resultData);

    }
    private function add(): string
    {
        $resultData = $this->controller->add();
        return json_encode($resultData);

    }

    private function search(): string
    {

        $resultData = $this->controller->search();
        return json_encode($resultData);
    }
    // 
    private function updateName(): string
    {
        $resultData = $this->controller->updateName($this->getInputAppId(), $this->getInputAppPackageName());
        return json_encode($resultData);
    }
    private function updateSha(): string
    {
        $resultData = $this->controller->updateSha($this->getInputAppId(), $this->getInputAppSha());
        return json_encode($resultData);
    }
    private function updateVersion(): string
    {
        $resultData = $this->controller->updateVersion($this->getInputAppId(), $this->getInputAppVersion());
        return json_encode($resultData);
    }
}

$this_class = new ThisClass();
die($this_class->main());
