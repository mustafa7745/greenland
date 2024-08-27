<?php
class ModelPermission
{
    public $id;
    public $name;
    public $createdAt;
    public $updatedAt;
    public function __construct($user)
    {
        $this->id = getId($user);
        $this->name = getName($user);
        $this->createdAt = getCreatedAt($user);
        $this->updatedAt = getUpdatedAt($user);
    }
}