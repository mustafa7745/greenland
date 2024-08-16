<?php
namespace SU1;

require_once ("../../../middleware.php");
require_once (getPath() . "app/su1/projects_apps/index.php");

class ThisClass
{

    use \SharedPostData;
    use \ProjectsPostData;
    use \AppsPostData;
    public ProjectsApps $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new ProjectsApps();
    }


    function main(): string
    {
        if ($this->getTag() == "readApps") {
            return $this->readApps();
        } elseif ($this->getTag() == "readProject") {
            return $this->readProject();
        } else {
            UNKOWN_TAG();
        }
    }
    //Main Functin CRUD
    private function readApps(): string
    {
        $resultData = $this->controller->readApps($this->getInputProjectId());
        return json_encode($resultData);

    }
    private function readProject(): string
    {
        $resultData = $this->controller->readProject($this->getInputAppId());
        return json_encode($resultData);

    }


}

$this_class = new ThisClass();
die($this_class->main());
