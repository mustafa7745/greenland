<?php

require_once __DIR__ . '/../products/attribute.php';
require_once __DIR__ . '/../offers/attribute.php';

require_once 'post_data.php';

class OffersProductsAttribute
{
    public $table_name = "offers_products";
    public $id = "id";
    public $offerId = "offerId";
    public $productId = "productId";
    public $productQuantity = "productQuantity";
    public $updatedAt = "updatedAt";
    public $createdAt = "createdAt";
    // 
    
  public $products_attribute;

  function initForignkey()
  {
    require_once __DIR__ . '/../products/attribute.php';
    $this->products_attribute = new ProductsAttribute();
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
      FORIGN_KEY_ID_INNER_JOIN($this->products_attribute->NATIVE_INNER_JOIN(), $this->table_name, $this->productId);
    return $inner;
  }
}
