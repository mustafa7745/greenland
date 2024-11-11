<?php
require_once 'post_data.php';

class LocationTypesAttribute
{
    public $table_name = "location_types";
    public $id = "id";
    public $name = "name";
    public $order = "order";
    public $createdAt = "createdAt";
    public $updatedAt = "updatedAt";
    function NATIVE_INNER_JOIN(): string
    {
      $inner = NATIVE_INNER_JOIN($this->table_name, $this->id);
      return $inner;
    }
}
