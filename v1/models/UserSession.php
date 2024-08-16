<?php
class ModelUserSession
{
    public $id;
    public $userId;
    public $deviceSessionId;
    public $createdAt;
    public $lastLoginAt;
    public function __construct($userSession)
    {
        $this->id = getId($userSession);
        $this->userId = getUserId($userSession);
        $this->deviceSessionId = getDeviceSessionId($userSession);
        $this->createdAt = getCreatedAt($userSession);
        $this->lastLoginAt = getLastLoginAt($userSession);
    }
}