<?php
namespace SU1;

require_once ("../../../middleware.php");
require_once (getPath() . "app/su1/permissions_groups/index.php");

class ThisClass
{

    use \SharedPostData;
    use \PermissionsPostData;
    use \GroupsPostData;

    public PermissionsGroups $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new PermissionsGroups();
    }


    function main(): string
    {
        if ($this->getTag() == "readGroups") {
            return $this->readGroups();
        } elseif ($this->getTag() == "readPermissions") {
            return $this->readPermissions();
        } elseif ($this->getTag() == "searchGroups") {
            return $this->searchGroups();
        } elseif ($this->getTag() == "searchPermissions") {
            return $this->searchPermissions();
        } elseif ($this->getTag() == "searchGroupsToAdd") {
            return $this->searchGroupsToAdd();
        } elseif ($this->getTag() == "searchPermissionsToAdd") {
            return $this->searchPermissionsToAdd();
        } elseif ($this->getTag() == "add") {
            return $this->add();
        } elseif ($this->getTag() == "updateName") {
            return $this->updateName();
        } elseif ($this->getTag() == "delete") {
            return $this->delete();
        } else {
            UNKOWN_TAG();
        }
    }
    //Main Functin CRUD
    private function readGroups(): string
    {
        $resultData = $this->controller->readGroups($this->getInputPermissionId());
        return json_encode($resultData);

    }
    private function readPermissions(): string
    {
        $resultData = $this->controller->readPermissions($this->getInputGroupId());
        return json_encode($resultData);

    }


    private function searchGroups(): string
    {

        $resultData = $this->controller->searchGroups($this->getInputPermissionId(), $this->getSearch());
        return json_encode($resultData);
    }
    private function searchGroupsToAdd(): string
    {

        $resultData = $this->controller->searchGroupsToAdd($this->getInputPermissionId(), $this->getSearch());
        return json_encode($resultData);
    }
    private function searchPermissions(): string
    {

        $resultData = $this->controller->searchPermissions($this->getInputGroupId(), $this->getSearch());
        return json_encode($resultData);
    }
    private function searchPermissionsToAdd(): string
    {

        $resultData = $this->controller->searchPermissionsToAdd($this->getInputGroupId(), $this->getSearch());
        return json_encode($resultData);
    }
    private function add(): string
    {
        $resultData = $this->controller->add($this->getInputPermissionId(), $this->getInputGroupId());
        return json_encode($resultData);
    }
    // 
    private function updateName(): string
    {
        $resultData = $this->controller->updateName($this->getInputGroupId(), $this->getInputGroupName());
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
