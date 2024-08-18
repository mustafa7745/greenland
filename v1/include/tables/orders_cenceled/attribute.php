<?php
require_once 'post_data.php';

require_once __DIR__ . '/../orders/attribute.php';

class OrdersCenceledAttribute
{
  public $table_name = "orders_cenceled";
  public $id = "id";
  public $orderId = "orderId";
  public $description = "description";
  public $createdAt = "createdAt";
}
