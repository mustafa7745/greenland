<?php
class ModelUser
{
    public $id;
    public $status;
    // public $phone;
    // public $createdAt;
    // public $lastLoginAt;
    public function __construct($user)
    {
        $this->id = getId($user);
        $this->status = $user['status'];
        // $this->deviceSessionId = getPhoneFromData($user);
        // $this->createdAt = getCreatedAt($user);
        // $this->lastLoginAt = getLastLoginAt($user);
    }
}