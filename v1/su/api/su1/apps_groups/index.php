<?php
namespace SU1;

require_once ("../../../middleware.php");
require_once (getPath() . "app/su1/apps_groups/index.php");

class ThisClass
{

    use \SharedPostData;
    use \AppsPostData;
    use \GroupsPostData;

    public AppsGroups $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new AppsGroups();
    }


    function main(): string
    {
        if ($this->getTag() == "readGroup") {
            return $this->readGroup();
        } elseif ($this->getTag() == "add") {
            return $this->add();
        } elseif ($this->getTag() == "delete") {
            return $this->delete();
        } else {
            UNKOWN_TAG();
        }
    }
    //Main Functin CRUD
    private function readGroup(): string
    {
        $resultData = $this->controller->readGroup($this->getInputAppId());
        return json_encode($resultData);

    }

    private function add(): string
    {
        $resultData = $this->controller->add($this->getInputAppId(), $this->getInputGroupId());
        return json_encode($resultData);
    }
    // 

    private function delete(): string
    {
        $this->controller->delete($this->getDeletedIds());
        return json_encode("");
    }

}

$this_class = new ThisClass();
die($this_class->main());
