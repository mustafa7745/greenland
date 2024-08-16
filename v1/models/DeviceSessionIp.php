<?php

class ModelDeviceSessionIp
{
    public $id;
    public function __construct($deviceSessionIp)
    {
        $this->id = getId($deviceSessionIp);
    }
}