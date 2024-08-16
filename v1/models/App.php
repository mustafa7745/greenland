<?php

class ModelApp
{
    public $id;
    public $groupId;
    public $projectId;
    public $version;
    public $expireAt;
    public function __construct($app)
    {
        $this->id = getId($app);
        $this->groupId = getGroupId($app);
        $this->projectId = getProjectId($app);
        $this->version = getVersion($app);
        $this->expireAt = getExpireAt($app);
    }
}