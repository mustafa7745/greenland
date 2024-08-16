<?php
class ModelDeliveryManLoginToken
{
    public $id;
    public $userSessionId;
    public $deliveryManId;
    public $token;
    public $createdAt;
    public $expireAt;
    public $updatedAt;
    public function __construct($deliveryManLoginToken)
    {
        $this->id = getId($deliveryManLoginToken);
        $this->userSessionId = getUserSessionId($deliveryManLoginToken);
        $this->deliveryManId = getDeliveryManId($deliveryManLoginToken);
        $this->token = getToken($deliveryManLoginToken);
        $this->createdAt = getCreatedAt($deliveryManLoginToken);
        $this->expireAt = getExpireAt($deliveryManLoginToken);
        $this->updatedAt = getUpdatedAt($deliveryManLoginToken);
    }
}