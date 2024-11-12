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
  public $paid = "paid";
  public $inrest = "inrest";

  public $systemOrderNumber = "systemOrderNumber";
  public $createdAt = "createdAt";
  public $updatedAt = "updatedAt";
  //////////

  public $orders_situations_attribute;
  function initForignkey()
  {
    require_once(__DIR__ . '/../orders_situations/attribute.php');
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

  // 
// public $NOT_PAID = null;
  public $PAID_ON_DELIVERY = "1";
  public $ELECTEONIC_PAID = "2";
  public $PAID_FROM_WALLET = "3";
  public $PAID_IN_STORE = "4";

  // 

  public $DELIVERY = null;
  public $SAFARY = "1";
  public $MAHALY = "2";
  public $FAMILY = "3";
  public $CAR = "4";





}
