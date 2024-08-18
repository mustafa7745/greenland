<?php
require_once 'post_data.php';

require_once __DIR__ . '/../products/attribute.php';


class OrdersProductsAttribute
{
  public $table_name = "orders_products";
  public $id = "id";
  public $orderId = "orderId";
  public $productId = "productId";
  public $productName = "productName";
  public $productPrice = "productPrice";
  public $productQuantity = "productQuantity";
  public $createdAt = "createdAt";
  public $updatedAt = "updatedAt";
  //////////
}
