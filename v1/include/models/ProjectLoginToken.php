<?php
class ModelProjectLoginToken
{
    public $id;
    public $userSessionId;
    public $projectId;
    public $token;
    public $createdAt;
    public $expireAt;
    public $updatedAt;
    public function __construct($projectLoginToken)
    {
        $this->id = getId($projectLoginToken);
        $this->userSessionId = getUserSessionId($projectLoginToken);
        $this->projectId = getProjectId($projectLoginToken);
        $this->token = getToken($projectLoginToken);
        $this->createdAt = getCreatedAt($projectLoginToken);
        $this->expireAt = getExpireAt($projectLoginToken);
        $this->updatedAt = getUpdatedAt($projectLoginToken);
    }
}