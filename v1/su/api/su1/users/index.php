<?php
namespace SU1;

require_once ("../../../middleware.php");
require_once (getPath() . "app/su1/users/index.php");

class ThisClass
{

    use \SharedPostData;
    use \UsersPostData;
    public Users $controller;
    // 

    function __construct()
    {
        $this->controller = new Users();
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
        } elseif ($this->getTag() == "updatePassword") {
            return $this->updatePassword();
        } elseif ($this->getTag() == "update2FAPassword") {
            return $this->update2FAPassword();
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
        $resultData = $this->controller->add($this->getInputUserName(), $this->getInputUserPhone(), $this->getInputUserPassword(), $this->getInputUser2FAPassword());
        return json_encode($resultData);
    }
    // 
    private function updateName(): string
    {
        $resultData = $this->controller->updateName($this->getInputUserId(), $this->getInputUserName());
        return json_encode($resultData);
    }
    private function updatePassword(): string
    {
        $resultData = $this->controller->updatePassword($this->getInputUserId(), $this->getInputUserPassword());
        return json_encode($resultData);
    }
    private function update2FAPassword(): string
    {
        $resultData = $this->controller->update2FAPassword($this->getInputUserId(), $this->getInputUser2FAPassword());
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
