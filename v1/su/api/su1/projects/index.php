<?php
namespace SU1;

require_once ("../../../middleware.php");
require_once (getPath() . "app/su1/projects/index.php");

class ThisClass
{

    use \SharedPostData;
    use \ProjectsPostData;
    use \UsersPostData;
    use \DevicesPostData;
    public Projects $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new Projects();
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
        } elseif ($this->getTag() == "updateImage") {
            return $this->updateImage();
        } elseif ($this->getTag() == "updatePassword") {
            return $this->updatePassword();
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
        $resultData = $this->controller->add($this->getInputUserId(), $this->getInputProjectName(), $this->getInputProjectIcon(), $this->getInputDeviceId());
        return json_encode($resultData);
    }
    // 
    private function updateName(): string
    {
        $resultData = $this->controller->updateName($this->getInputProjectId(), $this->getInputProjectName());
        return json_encode($resultData);
    }
    private function updatePassword(): string
    {
        $resultData = $this->controller->updatePassword($this->getInputProjectId(), $this->getInputProjectPassword());
        return json_encode($resultData);
    }
    private function updateImage(): string
    {
        $resultData = $this->controller->updateImage($this->getInputProjectId(), $this->getInputProjectIcon());
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
