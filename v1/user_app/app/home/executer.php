<?php
namespace UserApp;

class HomeExecuter
{
  function executeGetData()
  {
    require_once __DIR__ . '/../ads/helper.php';
    $ads = getAdsHelper()->getData();
    require_once __DIR__ . '/../categories/helper.php';
    $categories = getCategoriesHelper()->getData();
    require_once __DIR__ . '/../offers/helper.php';
    $offers = getOffersHelper()->getData();
    return ['ads' => $ads, 'categories' => $categories, 'offers' => $offers];
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