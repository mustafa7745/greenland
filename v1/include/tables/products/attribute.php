<?php
require_once 'post_data.php';


class ProductsAttribute
{
  public $table_name = "products";
  public $id = "id";
  public $name = "name";
  public $postPrice = "postPrice";
  public $prePrice = "prePrice";
  public $productGroupId = "productGroupId";
  public $number = "number";
  public $order = "order";
  public $isAvailable = "isAvailable";
  public $categoryId = "categoryId";
  public $createdAt = "createdAt";
  public $updatedAt = "updatedAt";
  //////////
  function path_image()
  {
    return getPath() . "images/products/";
  }

  public $products_groups_attribute;


  function initForignkey()
  {
    require_once (getPath() . 'tables/products_groups/attribute.php');
    $this->products_groups_attribute = new ProductsGroupsAttribute();
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
      FORIGN_KEY_ID_INNER_JOIN($this->products_groups_attribute->NATIVE_INNER_JOIN(), $this->table_name, $this->productGroupId);
    return $inner;
  }

  public $AVAILABLE = 1;
  public $NOT_AVAILABLE = 0;

}
