<?php
// require_once 'post_data.php';
require_once __DIR__ . '/../delivery_men/attribute.php';
require_once __DIR__ . '/../orders_delivery/attribute.php';


class AcceptanceAttribute
{
    public $table_name = "acceptance";
    public $id = "id";
    public $deliveryManId = "deliveryManId";
    public $orderDeliveryId = "orderDeliveryId";
    public $status = "status";
    public $createdAt = "createdAt";
    public $updatedAt = "updatedAt";
    // 
    public $WAIT_TO_ACCEPT_STATUS = 0;
    public $ACCEPTED_STATUS = 1;
    public $REJECTED_STATUS = 2;
    public $NOT_ANSWRED_STATUS = 3;
    public $CHANGED_TO_OTHER_STATUS = 4;
}
