<?php
class ModelManagerLoginToken
{
    public $id;
    public $userSessionId;
    public $managerId;
    public $token;
    public $createdAt;
    public $expireAt;
    public $updatedAt;
    public function __construct($deliveryManLoginToken)
    {
        $this->id = getId($deliveryManLoginToken);
        $this->userSessionId = getUserSessionId($deliveryManLoginToken);
        $this->managerId = $deliveryManLoginToken['managerId'];
        $this->token = getToken($deliveryManLoginToken);
        $this->createdAt = getCreatedAt($deliveryManLoginToken);
        $this->expireAt = getExpireAt($deliveryManLoginToken);
        $this->updatedAt = getUpdatedAt($deliveryManLoginToken);
    }
}