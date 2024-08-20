<?php
namespace UserApp;

require_once (getPath() . 'tables/categories/attribute.php');

// require_once "../../../ids_controller/helper.php";
require_once (getSuPath() . 'app/ids_controller/helper.php');
require_once (getSuPath() . 'app/products_images/helper.php');


require_once ('helper.php');
class ProductsExecuter
{
  function executeGetData()
  {
    $data = getProductsHelper()->getData(getInputCategoryId());
    $ids = [];
    for ($i = 0; $i < count($data); $i++) {
      array_push($ids, $data[$i][getProductsHelper()->id]);
    }
    if (count($ids) > 0) {
      $idsString = convertIdsListToStringSql($ids);
      $images = getProductsImagesHelper()->getData($idsString);
      for ($i = 0; $i < count($data); $i++) {
        $newImages = [];
        $productId = getId($data[$i]);
        for ($im=0; $im < count($images); $im++) { 
          if ($productId == $images[$im]["productId"]) {
            array_push($newImages,$images[$im]["image"]);
          }
        }
        $data[$i]["productImages"] = $newImages;
      }
    }
    // 
    return $data;
  }
}

$products_executer = null;
function getProductsExecuter()
{
  global $products_executer;
  if ($products_executer == null) {
    $products_executer = new ProductsExecuter();
  }
  return $products_executer;
}