<?php
namespace UserApp;

require_once 'helper.php';
class OffersProductsExecuter
{
  function executeGetData($offerId)
  {
    $data = getOffersProductsHelper()->getData($offerId);

    $ids = [];
    for ($i = 0; $i < count($data); $i++) {
      array_push($ids, $data[$i][getOffersProductsHelper()->productId]);
    }
    // 
    if (count($ids) > 0) {
      $idsString = convertIdsListToStringSql($ids);
      require_once __DIR__ . '/../products/helper.php';
      $products = getProductsHelper()->getDataByIds($idsString);
      require_once __DIR__ . '/../products_images/helper.php';
      $images = getProductsImagesHelper()->getData($idsString);
      for ($i = 0; $i < count($products); $i++) {
        $newImages = [];
        $productId = getId($products[$i]);
        for ($im = 0; $im < count($images); $im++) {
          if ($productId == $images[$im]["productId"]) {
            $images[$im]['image'] = $images[$im]['product_image_path'] . $images[$im]['image'];
            array_push($newImages, $images[$im]);
          }
        }
        $products[$i]["productImages"] = $newImages;
        $data["products"] = $products;
      }
    }
    return $data;
  }
}

$offers_products_executer = null;
function getOffersProductsExecuter()
{
  global $offers_products_executer;
  if ($offers_products_executer == null) {
    $offers_products_executer = new OffersProductsExecuter();
  }
  return $offers_products_executer;
}