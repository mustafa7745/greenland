<?php
class ModelPermission
{
    public $id;
    public $name;
    public $createdAt;
    public $lastLoginAt;
    public function __construct($user)
    {
        $this->id = getId($user);
        $this->name = getName($user);
        $this->createdAt = getCreatedAt($user);
        $this->lastLoginAt = getLastLoginAt($user);
    }
}