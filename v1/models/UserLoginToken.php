<?php
class ModelUserLoginToken
{
    public $id;
    public $userSessionId;
    public $loginToken;
    public $createdAt;
    public $expireAt;
    public $updatedAt;
    public function __construct($userLoginToken)
    {
        $this->id = getId($userLoginToken);
        $this->userSessionId = getUserSessionId($userLoginToken);
        $this->loginToken = getLoginToken($userLoginToken);
        $this->createdAt = getCreatedAt($userLoginToken);
        $this->expireAt = getExpireAt($userLoginToken);
        $this->updatedAt = getUpdatedAt($userLoginToken);
    }
}