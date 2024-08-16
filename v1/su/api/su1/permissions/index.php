<?php
namespace SU1;


require_once ("../../../middleware.php");
require_once (getPath() . "app/su1/permissions/index.php");

class ThisClass
{
    use \SharedPostData;
    use \PermissionsPostData;


    public Permissions $controller;


    function __construct()
    {

        $this->controller = new Permissions();
    }


    function main(): string
    {
        if ($this->getTag() == "read") {

            return $this->read();
        } elseif ($this->getTag() == "search") {
            return $this->search();
        } elseif ($this->getTag() == "add") {
            return $this->add();
        } elseif ($this->getTag() == "updateName") {
            return $this->updateName();
        } elseif ($this->getTag() == "updatePrice") {
            return $this->updatePrice();
        } elseif ($this->getTag() == "delete") {
            return $this->delete();
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
    private function add(): string
    {
        $resultData = $this->controller->add($this->getInputPermissionName());
        return json_encode($resultData);
    }
    // 
    private function updateName(): string
    {
        $resultData = $this->controller->updateName($this->getInputPermissionId(), $this->getInputPermissionName());
        return json_encode($resultData);
    }
    private function updatePrice(): string
    {
        $resultData = $this->controller->updatePrice($this->getInputPermissionId(), $this->getInputPermissionPrice());
        return json_encode($resultData);
    }
    private function delete(): string
    {
        $this->controller->delete($this->getDeletedIds());
        return json_encode("");
    }

}

$this_class = new ThisClass();
die($this_class->main());
