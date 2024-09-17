<?php

class OrdersStatusAttribute
{
  public $table_name = "orders_status";
  public $id = "id";
  public $orderId = "orderId";
  public $situationId = "situationId";
  public $createdAt = "createdAt";
  //////////\
  public $json;

  public $orders_situations_attribute;
  function initJson()
  {
    $this->initForignkey();
    $this->json = function ($data, $i) {
      return $this->jsonF($data, $i);
    };
  }
  /////json function
  function jsonF($data, $i)
  {
    return json_encode(
      array(
        "$this->id" => $data[$i]["$this->id"],
        "{$this->orders_situations_attribute->name}" => json_decode($this->orders_situations_attribute->jsonF($data, $i)),
        "$this->createdAt" => $data[$i]["$this->createdAt"]
      )
    );
  }
  // 
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
}
