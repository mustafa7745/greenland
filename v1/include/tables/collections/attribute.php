<?php
// require_once 'post_data.php';

require_once __DIR__ . '/../delivery_men/attribute.php';


class CollectionsAttribute
{
  public $table_name = "collections";
  public $id = "id";
  public $orderId = "orderId";
  public $deliveryManId = "deliveryManId";
  public $managerId = "managerId";
  public $price = "price";
  public $isCollect = "isCollect";
  public $createdAt = "createdAt";
  public $updatedAt = "updatedAt";
  //////////
  public $COLLECTED_STATE = 1;
  public $UNCOLLECTED_STATE = 0;
}
