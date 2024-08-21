<?php
require_once 'post_data.php';


class OrdersDiscountsAttribute
{
  public $table_name = "orders_discounts";
  public $id = "id";
  public $orderId = "orderId";
  public $type = "type";
  public $amount = "amount";
  public $createdAt = "createdAt";
  public $updatedAt = "updatedAt";
  //////////

  public $PERCENTAGE_TYPE = 0;
  public $MONEY_TYPE = 1;

}
