<?php
namespace UserApp;

class HomeExecuter
{
  function executeGetData()
  {
    require_once __DIR__ . '/../ads/helper.php';
    $ads = getAdsHelper()->getData();
    for ($i = 0; $i < count($ads); $i++) {
      $ads[$i]['image'] = $ads[$i]['ads_image_path'] . $ads[$i]['image'];
    }
    require_once __DIR__ . '/../categories/helper.php';
    $categories = getCategoriesHelper()->getData();
    require_once __DIR__ . '/../offers/helper.php';
    $offers = getOffersHelper()->getData();
    for ($i = 0; $i < count($offers); $i++) {
      $offers[$i]['image'] = $offers[$i]['offer_image_path'] . $offers[$i]['image'];
    }
    require_once __DIR__ . '/../products/helper.php';

    // $discounts = getProductsHelper()->getDataWithDiscounts();
    // for ($i = 0; $i < count($discounts); $i++) {
    //   $discounts[$i]['productImages'] = [];
    // }
    return ['user' => null, 'products' => [], 'ads' => $ads, 'categories' => $categories, 'offers' => $offers, 'discounts' => []];
  }
  function executeGetDataWithUser()
  {
    $runApp = getMainRunApp();
    // print_r(getModelMainRunApp()->app->projectId);
    $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $runApp);
    $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
    require_once __DIR__ . '/../users/helper.php';
    $user = getUsersHelper()->getDataById($userId);
    // 
    require_once __DIR__ . '/../ads/helper.php';
    $ads = getAdsHelper()->getData();
    for ($i = 0; $i < count($ads); $i++) {
      $ads[$i]['image'] = $ads[$i]['ads_image_path'] . $ads[$i]['image'];
    }
    require_once __DIR__ . '/../categories/helper.php';
    $categories = getCategoriesHelper()->getData();
    require_once __DIR__ . '/../offers/helper.php';
    $offers = getOffersHelper()->getData();
    for ($i = 0; $i < count($offers); $i++) {
      $offers[$i]['image'] = $offers[$i]['offer_image_path'] . $offers[$i]['image'];
    }
    require_once __DIR__ . '/../products/helper.php';

    // $discounts = getProductsHelper()->getDataWithDiscounts();
    // for ($i = 0; $i < count($discounts); $i++) {
    //   $discounts[$i]['productImages'] = [];
    // }
    return ['user' => $user, 'products' => $this->getProducts(), 'ads' => $ads, 'categories' => $categories, 'offers' => $offers, 'discounts' => []];
  }
  function executeGetDataWithUser2()
  {
    $runApp = getMainRunApp();
    // print_r(getModelMainRunApp()->app->projectId);
    $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $runApp);
    $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
    require_once __DIR__ . '/../users/helper.php';
    $user = getUsersHelper()->getDataById2($userId);
    // 
    require_once __DIR__ . '/../ads/helper.php';
    $ads = getAdsHelper()->getData();
    for ($i = 0; $i < count($ads); $i++) {
      $ads[$i]['image'] = $ads[$i]['ads_image_path'] . $ads[$i]['image'];
    }
    require_once __DIR__ . '/../categories/helper.php';
    $categories = getCategoriesHelper()->getData();
    require_once __DIR__ . '/../offers/helper.php';
    $offers = getOffersHelper()->getData();
    for ($i = 0; $i < count($offers); $i++) {
      $offers[$i]['image'] = $offers[$i]['offer_image_path'] . $offers[$i]['image'];
    }
    require_once __DIR__ . '/../products/helper.php';

    // $discounts = getProductsHelper()->getDataWithDiscounts();
    // for ($i = 0; $i < count($discounts); $i++) {
    //   $discounts[$i]['productImages'] = [];
    // }
    return ['user' => $user, 'ads' => $ads, 'categories' => $categories, 'offers' => $offers, 'discounts' => [], 'products' => $this->getProducts()];
  }

  function getProducts()
  {
    require_once __DIR__ . '/../products/helper.php';
    $data = getProductsHelper()->getData2();
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
    return $data;
  }
}

$home_executer = null;
function getHomeExecuter()
{
  global $home_executer;
  if ($home_executer == null) {
    $home_executer = new HomeExecuter();
  }
  return $home_executer;
}