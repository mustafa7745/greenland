<?php

require_once __DIR__ . '/../products/attribute.php';
require_once __DIR__ . '/../offers/attribute.php';

require_once 'post_data.php';

class OffersProductsAttribute
{
    public $table_name = "offers_products";
    public $id = "id";
    public $offerId = "offerId";
    public $productId = "productId";
    public $productQuantity = "productQuantity";
    public $updatedAt = "updatedAt";
    public $expireAt = "createdAt";
}
