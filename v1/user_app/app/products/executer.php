<?php
namespace UserApp;


require_once ('helper.php');
class ProductsExecuter
{
  function executeGetData($categoryId)
  {
    $data = getProductsHelper()->getData($categoryId);
    $ids = [];
    for ($i = 0; $i < count($data); $i++) {
      array_push($ids, $data[$i][getProductsHelper()->id]);
    }
    if (count($ids) > 0) {
      $idsString = convertIdsListToStringSql($ids);
      require_once __DIR__ . '/../products_images/helper.php';
      $images = getProductsImagesHelper()->getData($idsString);
      for ($i = 0; $i < count($data); $i++) {
        $newImages = [];
        $productId = getId($data[$i]);
        for ($im = 0; $im < count($images); $im++) {
          if ($productId == $images[$im]["productId"]) {
            $images[$im]['image'] = $images[$im]['product_image_path'] . $images[$im]['image'];
            array_push($newImages, $images[$im]);
          }
        }
        $data[$i]["productImages"] = $newImages;
      }
    }
    // 
    return $data;
  }
  function executeSearchData($productName)
  {
    $data = getProductsHelper()->searchData($productName);
    $ids = [];
    for ($i = 0; $i < count($data); $i++) {
      array_push($ids, $data[$i][getProductsHelper()->id]);
    }
    if (count($ids) > 0) {
      $idsString = convertIdsListToStringSql($ids);
      require_once __DIR__ . '/../products_images/helper.php';
      $images = getProductsImagesHelper()->getData($idsString);
      for ($i = 0; $i < count($data); $i++) {
        $newImages = [];
        $productId = getId($data[$i]);
        for ($im = 0; $im < count($images); $im++) {
          if ($productId == $images[$im]["productId"]) {
            $images[$im]['image'] = $images[$im]['product_image_path'] . $images[$im]['image'];
            array_push($newImages, $images[$im]);
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