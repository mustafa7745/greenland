<?php
require_once 'post_data.php';


class OrdersDeliveryAttribute
{
  public $table_name = "orders_delivery";
  public $id = "id";
  public $price = "price";
  public $actualPrice = "actualPrice";
  public $orderId = "orderId";
  public $userLocationId = "userLocationId";
  public $deliveryManId = "deliveryManId";
  public $isCollect = "isCollect";
  public $isWithOrder = "isWithOrder";
  public $createdAt = "createdAt";
  public $updatedAt = "updatedAt";
  //////////

  public $COLLECTED_STATE = 1;
  public $UNCOLLECTED_STATE = 0;

 
}
