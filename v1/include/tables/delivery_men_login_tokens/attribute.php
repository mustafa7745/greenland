<?php
require_once 'post_data.php';

class DeliveryMenLoginTokensAttribute
{
    public $table_name = "delivery_men_login_tokens";
    public $id = "id";
    public $userSessionId = "userSessionId";
    public $token = "token";
    public $deliveryManId = "deliveryManId";
    public $expireAt = "expireAt";
    public $createdAt = "createdAt";
    public $updatedAt = "updatedAt";
}
