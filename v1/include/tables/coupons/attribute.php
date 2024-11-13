<?php
require_once 'post_data.php';

// require_once __DIR__ . '/../delivery_men/attribute.php';


class CouponsAttribute
{
  public $table_name = "coupons";
  public $id = "id";
  public $userId = "userId";
  public $more = "more";
  public $type = "type";
  public $code = "code";
  public $status = "status";
  public $countUsed = "countUsed";
  public $lessAmount = "lessAmount";
  public $amount = "amount";

  public $createdAt = "createdAt";
  public $updatedAt = "updatedAt";
  //////////
  public $PERCENTAGE_TYPE = 0;
  public $MONEY_TYPE = 1;
}
