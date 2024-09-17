<?php
require_once 'post_data.php';


class OrdersAttribute
{
  public $table_name = "orders";
  public $id = "id";
  public $userId = "userId";
  public $managerId = "managerId";
  public $situationId = "situationId";
  public $code = "code";
  public $withApp = "withApp";
  public $systemOrderNumber = "systemOrderNumber";
  public $createdAt = "createdAt";
  public $updatedAt = "updatedAt";
  //////////

  public $orders_situations_attribute;
  function initForignkey()
  {
    require_once (__DIR__ . '/../orders_situations/attribute.php');
    $this->orders_situations_attribute = new OrdersSituationsAttribute();
  }

  function NATIVE_INNER_JOIN(): string
  {
    $inner = NATIVE_INNER_JOIN($this->table_name, $this->id);
    return $inner;
  }
  function INNER_JOIN(): string
  {
    $this->initForignkey();
    $inner =
      FORIGN_KEY_ID_INNER_JOIN($this->orders_situations_attribute->NATIVE_INNER_JOIN(), $this->table_name, $this->situationId);
    return $inner;
  }
  // 
  public $ORDER_STARTED = 0;
  public $ORDER_COMPLETED = 1;
  public $ORDER_CENCELED = 2;
  public $ORDER_VIEWD = 3;
  public $ORDER_ASSIGNED_DELIVERY_MAN = 20;
  public $ORDER_PREPARING = 25;
  public $ORDER_IN_ROAD = 30;





}
