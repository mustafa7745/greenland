<?php
class ModelUser
{
    public $id;
    // public $name;
    // public $phone;
    // public $createdAt;
    // public $lastLoginAt;
    public function __construct($user)
    {
        $this->id = getId($user);
        // $this->name = getName($user);
        // $this->deviceSessionId = getPhoneFromData($user);
        // $this->createdAt = getCreatedAt($user);
        // $this->lastLoginAt = getLastLoginAt($user);
    }
}