<?php

class OrdersSituationsAttribute
{
  public $name = "orderSituation";
  public $table_name = "orders_situations";
  public $id = "id";
  public $situation = "situation";
  public $createdAt = "createdAt";
  public $updatedAt = "updatedAt";
  //////////
  function NATIVE_INNER_JOIN(): string
  {
    $inner = NATIVE_INNER_JOIN($this->table_name, $this->id);
    return $inner;
  }
  function jsonF($data, $i)
    {
        return json_encode(
            array(
                "$this->id" => $data[$i]["$this->id"],
                "$this->situation" => $data[$i]["$this->situation"]
            )
        );
    }
}
