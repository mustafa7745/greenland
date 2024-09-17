<?php
require_once 'post_data.php';


class ProductsImagesAttribute
{
  public $name = "p_image";
  public $table_name = "products_images";
  public $id = "id";
  public $productId = "productId";
  public $image = "image";
  public $createdAt = "createdAt";
  public $updatedAt = "updatedAt";
  //////////
  function path_image()
  {
    return __DIR__ . "/../../images/products/";
  }
}
