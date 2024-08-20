<?php
namespace UserApp;

require_once ('sql.php');
// 
class ProductsImagesHelper extends ProductsImagesSql
{
  function getData($productIds)
  {
    $sql = $this->readByProductIdsSql($productIds);
    // print_r($sql);
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
}

$products_images_helper = null;
function getProductsImagesHelper()
{
  global $products_images_helper;
  if ($products_images_helper == null) {
    $products_images_helper = new ProductsImagesHelper();
  }
  return $products_images_helper;
}