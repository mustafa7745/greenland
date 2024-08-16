<?php

class ModelDeviceSession
{
    public $id;
    public $appToken;
    public function __construct($deviceSession)
    {
        $this->id = getId($deviceSession);
        $this->appToken = getAppDeviceToken($deviceSession);
    }
}