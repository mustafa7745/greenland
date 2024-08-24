<?php
namespace UserApp;

require_once 'helper.php';
class OffersProductsExecuter
{
  function executeGetData($offerId)
  {
    $data = getOffersProductsHelper()->getData($offerId);

    // $ids = [];
    // for ($i = 0; $i < count($data); $i++) {
    //   array_push($ids, $data[$i][getOffersProductsHelper()->productId]);
    // }
    // if (count($ids) > 0) {
    //   $idsString = convertIdsListToStringSql($ids);
    //   require_once __DIR__ . '/../products_images/helper.php';
    //   $images = getProductsImagesHelper()->getData($idsString);
    //   for ($i = 0; $i < count($data); $i++) {
    //     $newImages = [];
    //     $productId = getId($data[$i]);
    //     for ($im = 0; $im < count($images); $im++) {
    //       if ($productId == $images[$im]["productId"]) {
    //         $images[$im]['image'] = $images[$im]['product_image_path'] . $images[$im]['image'];
    //         array_push($newImages, $images[$im]);
    //       }
    //     }
    //     $data[$i]["productImages"] = $newImages;
    //   }
    // }
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