<?php
namespace UserApp;


require_once ('helper.php');
class OrdersExecuter
{
  function executeAddData($userId, $order_products, $orderOffers, $projectId, $userLocationId)
  {
    require_once __DIR__ . "/../projects/helper.php";
    $projectHelper = getProjectsHelper1();

    if ($projectHelper->getStatus() == "0") {
      $message = $projectHelper->getMessage();
      $ar = $message;
      $en = "";
      exitFromScript($ar, $en);
    }
    if (count($orderOffers) == 0 and count($order_products) == 0) {
      $ar = "يجب وجود منتج على الاقل";
      $en = "";
      exitFromScript($ar, $en);
    }
    $helper = getOrdersHelper();
    // 1) Ch
    shared_execute_sql("START TRANSACTION");

    $helper->checkIfhaveOrderNotComplete($userId);
    // ***** //Start Products
    $products = [];
    if (count($order_products) != 0) {
      $productsIds = [];
      for ($i = 0; $i < count($order_products); $i++) {
        array_push($productsIds, $order_products[$i]["id"]);
      }

      $idsStringSql = convertIdsListToStringSql($productsIds);

      require_once __DIR__ . "/../../app/products/helper.php";
      $products = getProductsHelper()->getDataByIds($idsStringSql);


      if (count($products) == 0) {
        $ar = "IDS_NOT_HAVE_ProductsDB";
        $en = "IDS_NOT_HAVE_ProductsDB";
        exitFromScript($ar, $en);
      }
      if (count($products) != count($productsIds)) {
        $ar = "Product_Count_not_same_ProductsDB";
        $en = "Product_Count_not_same_ProductsDB";
        exitFromScript($ar, $en);
      }

    }
    // End Products

    // ***** //Start Offers
    $offers = [];

    if (count($orderOffers) != 0) {
      $offerIds = [];
      for ($i = 0; $i < count($orderOffers); $i++) {
        array_push($offerIds, $orderOffers[$i]["id"]);
      }

      $idsStringSql = convertIdsListToStringSql($offerIds);

      require_once __DIR__ . "/../../app/offers/helper.php";
      $offers = getOffersHelper()->getDataByIds($idsStringSql);


      if (count($offers) == 0) {
        $ar = "IDS_NOT_HAVE_OffersDB";
        $en = "IDS_NOT_HAVE_offersDB";
        exitFromScript($ar, $en);
      }
      if (count($offers) != count($offerIds)) {
        $ar = "Offer_Count_not_same_ProductsDB";
        $en = "Offer_Count_not_same_ProductsDB";
        exitFromScript($ar, $en);
      }
    }
    // End Offers


    /**
     * ADD ORDER DATA
     */

    getOrdersHelper()->addOrder($userId);
    $orderId = getDB()->conn->insert_id;



    /**
     * ADD DELIVERY DATA
     */
    require_once __DIR__ . "/../../../include/projects/helper.php";

    $project = getProjectsHelper()->getDataById($projectId);
    $project_lat = (getLatLong($project))[0];
    $project_long = (getLatLong($project))[1];
    // 
    require_once __DIR__ . "/../../app/users_locations/helper.php";

    $userLocation = getUsersLocationsHelper()->getDataById($userLocationId);
    $user_lat = (getLatLong($userLocation))[0];
    $user_long = (getLatLong($userLocation))[1];
    // 
    $distanse = haversine_distance($project_lat, $project_long, $user_lat, $user_long);
    // print_r($distanse);
    $order_price_delivery = 50 * round(($distanse * getPriceDeliveryPer1Km($project)) / 50);
    // 
    $orderDeliveryId = uniqid(rand(), false);
    getOrdersDeliveryHelper()->addData($orderDeliveryId, $orderId, $order_price_delivery, $order_price_delivery, $userLocationId);


    for ($i = 0; $i < count($products); $i++) {
      $productId = $products[$i][getProductsHelper()->id];
      $productName = $products[$i][getProductsHelper()->name];
      $productPrice = $products[$i][getProductsHelper()->postPrice];
      $productQuantity = getQntFromOrderProducts($order_products, $productId);
      getOrdersProductsHelper()->addOrderProducts($orderId, $productId, $productName, $productPrice, $productQuantity);
    }





    for ($i = 0; $i < count($offers); $i++) {
      $offerId = $offers[$i][getOffersHelper()->id];
      $offerName = $offers[$i][getOffersHelper()->name];
      $offerPrice = $offers[$i][getOffersHelper()->price];
      $offerQuantity = getQntFromOrderProducts($orderOffers, $offerId);
      // $id = uniqid(rand(), false);
      getOrdersOffersHelper()->addOrderOffer($orderId, $offerId, $offerName, $offerPrice, $offerQuantity);
    }

    require_once __DIR__ . '/../../../include/shared_app/order-content/index.php';
    $orderProducts = (new \OrderContent());
    $orderProducts->executeGetData($orderId);
    /**
     * ADD INSERTED VALUES TO UserINSERtOperations TABLE
     */

    // sharedAddUserInsertOperation($data->getUserId(), $data->getPermissionId(), $data->getUserSessionId(), $orderProducts);

    // print_r($orderProducts);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    require_once __DIR__ . '/../../../include/shared/helper_functions.php';
    $w = new \ApiWhatsapp();
    $message = "طلب جديد" . " $orderId ";
    $w->sendMessageText("967774519161", $message);
    return $orderProducts;


  }
  /********/
  function executeGetData($userId, $offset)
  {
    $data = getOrdersHelper()->getData($userId, $offset);
    return $data;
  }
}
$orders_executer = null;
function getOrdersExecuter()
{
  global $orders_executer;
  if ($orders_executer == null) {
    $orders_executer = (new OrdersExecuter());
  }
  return $orders_executer;
}
/********/
class OrdersProductsExecuter
{
  function executeGetData($orderId)
  {
    // $order = getOrdersHelper()->getOrder($order_id);
    // $currency_id = getOrdersHelper()->getOrderCurrencyId($order);
    // require_once (getPath() . "app/user_app/projects_currencies/helper.php");
    // $project_currency = getProjectsCurrenciesHelper()->getDataByCurrencyIdAndProjectId($currency_id, $project_id);
    // $orderProducts = getOrdersProductsHelper()->getOrderProductsByOrderWithItsStuff1($orderId);
    require_once __DIR__ . '/../../../include/shared_app/order-content/index.php';
    $data = (new \OrderContent());
    $data->executeGetData($orderId);

    return $data;
  }
}
$orders_products_executer = null;
function getOrdersProductsExecuter()
{
  global $orders_products_executer;
  if ($orders_products_executer == null) {
    $orders_products_executer = (new OrdersProductsExecuter());
  }
  return $orders_products_executer;
}
/********/
class OrdersStatusExecuter
{
  function executeGetData($orderId)
  {
    // $order = getOrdersHelper()->getOrder($order_id);
    // print_r("ff");
    $data = getOrdersStatusHelper()->getOrderStatus($orderId);
    return $data;
  }
}
$orders_status_executer = null;
function getOrdersStatusExecuter()
{
  global $orders_status_executer;
  if ($orders_status_executer == null) {
    $orders_status_executer = (new OrdersStatusExecuter());
  }
  return $orders_status_executer;
}

