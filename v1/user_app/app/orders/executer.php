<?php
namespace UserApp;


require_once ('helper.php');
class OrdersExecuter
{
  function executeAddData($userId, $order_products, $projectId, $userLocationId)
  {
    $helper = getOrdersHelper();
    // 1) Ch
    $helper->checkIfhaveOrderNotComplete($userId);

    $ids = [];
    for ($i = 0; $i < count($order_products); $i++) {
      array_push($ids, $order_products[$i]["id"]);
    }


    $idsStringSql = convertIdsListToStringSql($ids);
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    require_once __DIR__ . "/../../app/products/helper.php";
    $products = getProductsHelper()->getDataByIds($idsStringSql);


    if (count($products) == 0) {
      $ar = "IDS_NOT_HAVE_ProductsDB";
      $en = "IDS_NOT_HAVE_ProductsDB";
      exitFromScript($ar, $en);
    }
    if (count($products) != count($ids)) {
      $ar = "Product_Count_not_same_ProductsDB";
      $en = "Product_Count_not_same_ProductsDB";
      exitFromScript($ar, $en);
    }


    /**
     * ADD ORDER DATA
     */
    require_once __DIR__ . "/../../app/ids_controller/helper.php";

    $orderId = getId(getIdsControllerHelper()->getData($helper->table_name));
    getOrdersHelper()->addOrder($orderId, $userId);


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


    // $order = getOrdersHelper()->getOrder($order_id);



    $final_sum = 0.0;
    for ($i = 0; $i < count($products); $i++) {
      $productId = $products[$i][getProductsHelper()->id];
      $productName = $products[$i][getProductsHelper()->name];
      $productPrice = $products[$i][getProductsHelper()->postPrice];
      $final_sum = $final_sum + $productPrice;
      $productQuantity = getQntFromOrderProducts($order_products, $productId);
      $id = uniqid(rand(), false);
      getOrdersProductsHelper()->addOrderProducts($id, $orderId, $productId, $productName, $productPrice, $productQuantity);
    }

    $orderProducts = getOrdersProductsHelper()->getOrderProductsByOrderWithItsStuff1($orderId);

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
    $message = "طلب جديد" . " {$orderProducts['orderId']} ";
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

