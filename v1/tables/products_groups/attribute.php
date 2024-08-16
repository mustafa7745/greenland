<?php
require_once 'post_data.php';


class ProductsGroupsAttribute
{
  public $tableShortName = "productGroup";
  public $table_name = "products_groups";
  public $id = "id";
  public $name = "name";
  public $categoryId = "categoryId";
  public $createdAt = "createdAt";
  public $updatedAt = "updatedAt";
  function NATIVE_INNER_JOIN(): string
  {
    $inner = NATIVE_INNER_JOIN($this->table_name, $this->id);
    return $inner;
  }

}  //////////

