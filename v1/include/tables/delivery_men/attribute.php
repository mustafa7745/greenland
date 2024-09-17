<?php
require_once 'post_data.php';

class DeliveryMenAttribute
{
    public $name = "deliveryMan";
    public $table_name = "delivery_men";
    public $id = "id";
    public $userId = "userId";
    public $createdAt = "createdAt";
    // 
    public $users_attribute;

    /////json function

    // 
    function initForignkey()
    {
        require_once (__DIR__ . '/../users/attribute.php');
        $this->users_attribute = new UsersAttribute();
    }
    function INNER_JOIN(): string
    {
        $this->initForignkey();
        $inner =
            FORIGN_KEY_ID_INNER_JOIN($this->users_attribute->NATIVE_INNER_JOIN(), $this->table_name, $this->userId);
        return $inner;
    }
}
