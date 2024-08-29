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

    $discounts = getProductsHelper()->getDataWithDiscounts();
    return ['user' => null,'ads' => $ads, 'categories' => $categories, 'offers' => $offers, 'discounts' => $discounts];
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

    $discounts = getProductsHelper()->getDataWithDiscounts();
    return ['user' => $user, 'ads' => $ads, 'categories' => $categories, 'offers' => $offers, 'discounts' => $discounts];
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