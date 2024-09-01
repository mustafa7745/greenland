<?php
namespace Manager;


require_once ('sql.php');
require_once ('helper.php');
class OrdersExecuter extends OrdersSql
{
  function executeAddData($userId, $order_products, $projectId, $userLocationId, $deliveryManId)
  {
    $helper = getOrdersHelper();
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

    require_once __DIR__ . "/../products/helper.php";
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
    require_once __DIR__ . "/../../../include/ids_controller/helper.php";

    $orderId = getId(getIdsControllerHelper()->getData($helper->table_name));
    getOrdersHelper()->addOrder($orderId, $userId);





    // $order = getOrdersHelper()->getOrder($order_id);



    $final_sum = 0.0;
    for ($i = 0; $i < count($products); $i++) {
      $id = uniqid(rand(), false);
      $productId = $products[$i][getProductsHelper()->id];
      $productName = $products[$i][getProductsHelper()->name];
      $productPrice = $products[$i][getProductsHelper()->postPrice];
      $final_sum = $final_sum + $productPrice;
      $productQuantity = getQntFromOrderProducts($order_products, $productId);
      getOrdersProductsHelper()->addOrderProducts($id, $orderId, $productId, $productName, $productPrice, $productQuantity);
    }

    if ($deliveryManId != null) {
      ////// 1)
      /**
       * ADD DELIVERY DATA
       */
      require_once __DIR__ . '/../../../include/projects/helper.php';

      $project = getProjectsHelper()->getDataById($projectId);
      $project_lat = (getLatLong($project))[0];
      $project_long = (getLatLong($project))[1];
      // 
      require_once __DIR__ . "/../users_locations/helper.php";

      $userLocation = getUsersLocationsHelper()->getDataById($userLocationId);
      $user_lat = (getLatLong($userLocation))[0];
      $user_long = (getLatLong($userLocation))[1];
      // 
      $distanse = haversine_distance($project_lat, $project_long, $user_lat, $user_long);
      // print_r($distanse);
      $order_price_delivery = 50 * round(($distanse * getPriceDeliveryPer1Km($project)) / 50);
      //
      ///////// 2)


      $orderDeliveryId = uniqid(rand(), false);
      getOrdersDeliveryHelper()->addData($orderDeliveryId, $orderId, $order_price_delivery, $userLocationId);



      // 1) Get Reservation Data
      require_once (getManagerPath() . 'app/reservations/helper.php');
      $resrvation = getReservationsHelper()->getData($deliveryManId);
      $resrvation = getReservationsHelper()->getDataById(getId($resrvation));
      // 2) Update Reservation status to Accepted
      require_once __DIR__ . "/../acceptance/helper.php";
      getReservationsHelper()->updateStatus(getId($resrvation), getReservationsHelper()->ACCEPTED_RESERVED_STATUS);
      // 3) Add Request Accept To Acceptance Table
      $acceptanceId = getId(getIdsControllerHelper()->getData($helper->table_name));
      getAcceptanceHelper()->addData($acceptanceId, $deliveryManId, $orderDeliveryId);

    }
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    getDeliveryMenExecuter()->sendMessageToDeliveryMan($deliveryManId);
    return ["success" => "true"];


  }
  /********/
  function executeGetData()
  {
    $data = getOrdersHelper()->getData();
    return $data;
  }
  // 

  function executeSearchData($orderId)
  {
    $data = getOrdersHelper()->searchDataById($orderId);
    return $data;
  }
  function executeUpdateSystemOrder($orderId, $systemOrderNumber)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    getOrdersHelper()->updateSystemOrderNumber($orderId, $systemOrderNumber);
    $situatinId = getOrdersHelper()->ORDER_PREPARING;
    getOrdersHelper()->updateStatus($orderId, $situatinId);
    getOrdersStatusHelper()->addData($orderId, $situatinId);
    // $data = getOrdersHelper()->getData();
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    $order = getOrdersHelper()->getDataById($orderId);
    $userId = $order[getOrdersHelper()->userId];
    require_once __DIR__ . '/../../app/users/helper.php';
    $user = getUsersHelper()->getDataById($userId);
    require_once __DIR__ . '/../../../include/users_sessions_devices_sessions/helper.php';
    $token = getUsersSessionsHelper()->getToken($userId, 2);
    if ($token != null) {
      require_once __DIR__ . '/../../../include/projects/helper.php';
      $project = getProjectsHelper()->getDataById(1);
      require_once __DIR__ . '/../../../include/send_message.php';
      $title = "مرحبا بك: " . $user[getUsersHelper()->name];
      sendMessageToOne($project[getProjectsHelper()->serviceAccountKey], $token, $title, "يتم الان تجهيز طلبك");
    }
    return ["success" => "true"];
    // return $data;
  }
  function executeGetFinalOrderPriceWithoutDeliveryPrice($orderId)
  {
    $finalPrice = 0;
    // 1) products final price
    $productFinalPrice = 0;
    $orderProducts = getOrdersProductsHelper()->getOrderProductsByOrderId2($orderId);
    for ($i = 0; $i < count($orderProducts); $i++) {
      $productFinalPrice += $orderProducts[$i]["avg"];
    }
    $finalPrice = $productFinalPrice;
    return $finalPrice;
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
  function executeAddData($orderId, $productId, $productQuantity)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $order = getOrdersHelper()->getDataById($orderId);
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    // 
    require_once __DIR__ . '/../products/helper.php';
    $orderProduct = getOrdersProductsHelper()->getDataByOrderIdAndProductId($orderId, $productId);
    if (count($orderProduct) != 0) {
      $ar = "هذا المنتج موجود في الطلب";
      $en = "هذا المنتج موجود في الطلب";
      exitFromScript($ar, $en);
    }
    $product = getProductsHelper()->getDataById($productId);
    $productName = $product[getProductsHelper()->name];
    $productPrice = $product[getProductsHelper()->postPrice];
    $id = uniqid(rand(), false);
    getOrdersProductsHelper()->addOrderProducts($id, $orderId, $productId, $productName, $productPrice, $productQuantity);
    $data = getOrdersProductsHelper()->getDataById($id);
    shared_execute_sql("COMMIT");
    return $data;

  }
  function executeCencelOrder($orderId, $description)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    $order = getOrdersHelper()->getDataById($orderId);
    // 

    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    $situationId = getOrdersHelper()->ORDER_CENCELED;
    getOrdersHelper()->updateStatus($orderId, $situationId);
    getOrdersStatusHelper()->addData($orderId, $situationId);
    getOrdersCenceledHelper()->addData($orderId, $description);
    $orderDelivery = getOrdersDeliveryHelper()->getDataByOrderId($orderId);

    require_once __DIR__ . '/../acceptance/helper.php';
    $acceptance = getAcceptanceHelper()->getDataByOrderDeliveryIdAndStatus(getId($orderDelivery), getAcceptanceHelper()->WAIT_TO_ACCEPT_STATUS);
    if (count($acceptance) == 1) {
      $acceptance = $acceptance[0];

      $ids = [getId($acceptance)];



      $idsString = convertIdsListToStringSql($ids);

      // print_r($ids);
      // print_r($idsString);

      // exitFromScript(json_encode($ids), "ffdf");
      getAcceptanceHelper()->deleteData($idsString, count($ids));
    }
    shared_execute_sql("COMMIT");
    return ['success' => 'true'];
  }
  function executeGetData($orderId)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    $order = getOrdersHelper()->getDataById($orderId);
    // 
    // print_r($order);
    // exitFromScript("",json_encode($order));
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_STARTED) {
      $situatinId = getOrdersHelper()->ORDER_VIEWD;
      getOrdersHelper()->updateStatus(getId($order), $situatinId);
      getOrdersStatusHelper()->addData(getId($order), $situatinId);
    }

    // $order = getOrdersHelper()->getOrder($order_id);
    // $currency_id = getOrdersHelper()->getOrderCurrencyId($order);
    // require_once (getPath() . "app/user_app/projects_currencies/helper.php");
    // $project_currency = getProjectsCurrenciesHelper()->getDataByCurrencyIdAndProjectId($currency_id, $project_id);
    // $orderProducts = getOrdersProductsHelper()->getOrderProductsByOrderWithItsStuff2($orderId);
    $orderProducts = getOrdersProductsHelper()->getDataByOrderId($orderId);
    $delivery = getOrdersDeliveryHelper()->getDataByOrderId2($orderId);
    $discount = getOrdersDiscountsHelper()->getDataByOrderId($orderId);
    $offers = getOrdersOffersHelper()->getDataByOrderId($orderId);

    shared_execute_sql("COMMIT");

    return ['products' => $orderProducts, 'delivery' => $delivery, 'discount' => $discount, 'offers' => $offers];
  }
  function executeUpdateQuantity($id, $newValue)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $orderProduct = getOrdersProductsHelper()->getDataById($id);
    $order = getOrdersHelper()->getDataById($orderProduct[getOrdersProductsHelper()->orderId]);
    // 
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    // 
    getOrdersProductsHelper()->updateQuantity($id, $newValue);
    $data = getOrdersProductsHelper()->getDataById($id);
    shared_execute_sql("COMMIT");
    return $data;
  }

  function executeGetOrdersByUserId($userId)
  {
    return getOrdersHelper()->getDataByUserId($userId);
  }
  function executeDeleteData($ids)
  {
    $idsString = convertIdsListToStringSql($ids);
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $orderProduct = getOrdersProductsHelper()->getDataById($ids[0]);
    $order = getOrdersHelper()->getDataById($orderProduct[getOrdersProductsHelper()->orderId]);
    // 
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    getOrdersProductsHelper()->deleteData($idsString, count($ids));
    // getOrdersProductsHelper()->updateQuantity($id, $newValue);
    // $data = getOrdersProductsHelper()->getDataById($id);
    shared_execute_sql("COMMIT");
    return ['success' => "true"];
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
// 
class OrdersDeliveryExecuter
{
  function executeGetUncollectedOrders($deliveryManId)
  {
    $data = getOrdersDeliveryHelper()->getDataUncollected($deliveryManId);
    return $data;
  }
  function executeUpdateActualPrice($id, $newValue)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $orderDelivery = getOrdersDeliveryHelper()->getDataById($id);
    $order = getOrdersHelper()->getDataById($orderDelivery[getOrdersDeliveryHelper()->orderId]);
    // 
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    // 
    getOrdersDeliveryHelper()->updateActualPrice($id, $newValue);
    $data = getOrdersDeliveryHelper()->getDataById($id);
    shared_execute_sql("COMMIT");
    return $data;
  }
}
$orders_delivery_executer = null;
function getOrdersDeliveryExecuter()
{
  global $orders_delivery_executer;
  if ($orders_delivery_executer == null) {
    $orders_delivery_executer = new OrdersDeliveryExecuter();
  }
  return $orders_delivery_executer;
}
// 
class OrdersDiscountsExecuter
{
  function executeAddData($orderId, $amount, $type)
  {
    $id = uniqid(rand(), false);
    return getOrdersDiscountsHelper()->addData($id, $orderId, $amount, $type);
  }
  function executeUpdateType($id, $type)
  {
    shared_execute_sql("START TRANSACTION");
    $updatedData = getOrdersDiscountsHelper()->updateType($id, $type);
    shared_execute_sql("COMMIT");
    return $updatedData;
  }
  function executeUpdateAmount($id, $amount)
  {
    shared_execute_sql("START TRANSACTION");
    $updatedData = getOrdersDiscountsHelper()->updateAmount($id, $amount);
    shared_execute_sql("COMMIT");
    return $updatedData;
  }
  function executeDeleteData($id)
  {
    return getOrdersDiscountsHelper()->deleteData($id);
  }

}
$orders_discounts_executer = null;
function getOrdersDiscountsExecuter()
{
  global $orders_discounts_executer;
  if ($orders_discounts_executer == null) {
    $orders_discounts_executer = new OrdersDiscountsExecuter();
  }
  return $orders_discounts_executer;
}

