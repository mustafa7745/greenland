<?php

require_once __DIR__ . '/../offers/attribute.php';
require_once __DIR__ . '/../orders/attribute.php';

require_once 'post_data.php';

class OrdersOffersAttribute
{
    public $table_name = "orders_offers";
    public $id = "id";
    public $offerId = "offerId";
    public $orderId = "orderId";
    public $offerQuantity = "offerQuantity";
    public $offerPrice = "offerPrice";
    public $updatedAt = "updatedAt";
    public $expireAt = "createdAt";
}
