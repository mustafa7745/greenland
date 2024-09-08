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
  public $isWithOrder = "isWithOrder";
  public $createdAt = "createdAt";
  public $updatedAt = "updatedAt";
  //////////

  public $COLLECTED_STATE = 1;
  public $UNCOLLECTED_STATE = 0;

  public $orders_attribute;

  function initForignkey()
  {
    require_once __DIR__ . '/../orders/attribute.php';
    $this->orders_attribute = new OrdersAttribute();
  }
  function INNER_JOIN(): string
  {
    $this->initForignkey();
    $inner =
      FORIGN_KEY_ID_INNER_JOIN($this->orders_attribute->NATIVE_INNER_JOIN(), $this->table_name, $this->orderId);
    return $inner;
  }




}
