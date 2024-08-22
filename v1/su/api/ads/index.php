<?php
namespace SU1;

require_once "../../app/ads/index.php";


class ThisClass
{
    // use \AppsPostData;
    public Ads $controller;
    // 

    function __construct()
    {
        //
        $this->controller = new Ads();
    }


    function main(): string
    {
        if (getTag() == "read") {
            return $this->read();
        } elseif (getTag() == "add") {
            return $this->add();
        } elseif (getTag() == "updateImage") {
            return $this->updateImage();
        } elseif (getTag() == "updateDescription") {
            return $this->updateDescription();
        } elseif (getTag() == "updateIsEnabled") {
            return $this->updateIsEnabled();
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
    private function add(): string
    {
        $resultData = $this->controller->add();
        return json_encode($resultData);

    }
    private function updateImage(): string
    {
        $resultData = $this->controller->updateImage();
        return json_encode($resultData);
    }
    private function updateDescription(): string
    {
        $resultData = $this->controller->updateDescription();
        return json_encode($resultData);
    }
    private function updateIsEnabled(): string
    {
        $resultData = $this->controller->updateIsEnabled();
        return json_encode($resultData);
    }
}

$this_class = new ThisClass();
die($this_class->main());
