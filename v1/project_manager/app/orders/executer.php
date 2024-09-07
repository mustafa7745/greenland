<?php
namespace Manager;


require_once ('sql.php');
require_once ('helper.php');
class OrdersExecuter extends OrdersSql
{
  function executeAddData($userId, $order_products, $projectId, $userLocationId, $deliveryManId, $managerId)
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
    getOrdersHelper()->updateManagerId($orderId, $managerId);




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
  function executeGetData($managerId)
  {
    $data = getOrdersHelper()->getData($managerId);
    return $data;
  }
  // 

  function executeSearchData($orderId)
  {
    $data = getOrdersHelper()->searchDataById($orderId);
    return $data;
  }
  function executeUpdateSystemOrder($orderId, $systemOrderNumber, $managerId)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $order = getOrdersHelper()->getDataById($orderId);
    checkOrderOwner($order, $managerId);
    getOrdersHelper()->updateSystemOrderNumber($orderId, $systemOrderNumber);
    $situatinId = getOrdersHelper()->ORDER_PREPARING;
    getOrdersHelper()->updateStatus($orderId, $situatinId);
    getOrdersStatusHelper()->addData($orderId, $situatinId);
    // $data = getOrdersHelper()->getData();
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    // $order = getOrdersHelper()->getDataById($orderId);
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
  function executeAddData($orderId, $productId, $productQuantity, $managerId)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $order = getOrdersHelper()->getDataById($orderId);
    checkOrderOwner($order, $managerId);

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
  function executeCencelOrder($orderId, $description, $managerId)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    $order = getOrdersHelper()->getDataById($orderId);
    // 
    checkOrderOwner($order, $managerId);
    if ($order[getOrdersHelper()->managerId] == null) {
      getOrdersHelper()->updateManagerId($order[getOrdersHelper()->id], $managerId);
    }

    getOrdersHelper()->updateManagerId($order[getOrdersHelper()->id], $managerId);


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
  function executeGetData($orderId, $managerId)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    $order = getOrdersHelper()->getDataById($orderId);
    // checkOrderOwner($order, $managerId);

    // 
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_STARTED) {
      $situatinId = getOrdersHelper()->ORDER_VIEWD;
      getOrdersHelper()->updateStatus(getId($order), $situatinId);
      getOrdersStatusHelper()->addData(getId($order), $situatinId);
      getOrdersHelper()->updateManagerId($order[getOrdersHelper()->id], $managerId);
    }
    // 
    require_once __DIR__ . '/../../../include/shared_app/order-content/index.php';
    $data = (new \OrderContent());
    $data->executeGetData($orderId);
    shared_execute_sql("COMMIT");
    return $data;
  }
  function executeUpdateQuantity($id, $newValue, $managerId)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $orderProduct = getOrdersProductsHelper()->getDataById($id);
    $order = getOrdersHelper()->getDataById($orderProduct[getOrdersProductsHelper()->orderId]);
    checkOrderOwner($order, $managerId);
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
  function executeDeleteData($ids, $managerId)
  {
    $idsString = convertIdsListToStringSql($ids);

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $orderProduct = getOrdersProductsHelper()->getDataById($ids[0]);
    $order = getOrdersHelper()->getDataById($orderProduct[getOrdersProductsHelper()->orderId]);

    checkOrderOwner($order, $managerId);

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

class OrdersOffersExecuter
{
  function executeAddData($orderId, $offerId, $offerQuantity, $managerId)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $order = getOrdersHelper()->getDataById($orderId);
    checkOrderOwner($order, $managerId);

    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    // 
    require_once __DIR__ . '/../offers/helper.php';
    $orderOffer = getOrdersOffersHelper()->getDataByOrderIdAndOfferId($orderId, $offerId);
    if (count($orderOffer) != 0) {
      $ar = "هذا العرض موجود في الطلب";
      $en = "هذا العرض موجود في الطلب";
      exitFromScript($ar, $en);
    }
    $offer = getOffersHelper()->getDataById($offerId);
    $offerName = $offer[getOffersHelper()->name];
    $offerPrice = $offer[getOffersHelper()->price];
    $id = uniqid(rand(), false);
    getOrdersOffersHelper()->addOrderOffers($id, $orderId, $offerName, $offerId, $offerQuantity, $offerPrice);
    $data = getOrdersOffersHelper()->getDataById($id);
    shared_execute_sql("COMMIT");
    return $data;

  }
  function executeUpdateQuantity($id, $newValue, $managerId)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $orderOffer = getOrdersOffersHelper()->getDataById($id);
    $order = getOrdersHelper()->getDataById($orderOffer[getOrdersOffersHelper()->orderId]);
    checkOrderOwner($order, $managerId);

    // 
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    // 
    getOrdersOffersHelper()->updateQuantity($id, $newValue);
    $data = getOrdersOffersHelper()->getDataById($id);
    shared_execute_sql("COMMIT");
    return $data;
  }
  function executeDeleteData($ids, $managerId)
  {
    $idsString = convertIdsListToStringSql($ids);
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $orderOffers = getOrdersOffersHelper()->getDataById($ids[0]);
    $order = getOrdersHelper()->getDataById($orderOffers[getOrdersOffersHelper()->orderId]);
    checkOrderOwner($order, $managerId);

    // 
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    getOrdersOffersHelper()->deleteData($idsString, count($ids));
    // getOrdersProductsHelper()->updateQuantity($id, $newValue);
    // $data = getOrdersProductsHelper()->getDataById($id);
    shared_execute_sql("COMMIT");
    return ['success' => "true"];
  }
}
$orders_offers_executer = null;
function getOrdersOffersExecuter()
{
  global $orders_offers_executer;
  if ($orders_offers_executer == null) {
    $orders_offers_executer = (new OrdersOffersExecuter());
  }
  return $orders_offers_executer;
}
// ************ //

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
  function executeGetData($orderId)
  {
    $orderDelivery = getOrdersDeliveryHelper()->getDataByOrderId($orderId);
    require_once __DIR__ . "/../acceptance/helper.php";
    $acceptance = getAcceptanceHelper()->getData($orderDelivery[getOrdersDeliveryHelper()->id]);
    // if ($acceptance != null) {
    //   require_once (getManagerPath() . "app/delivery_men/helper.php");
    //   $deliveryMan = getDeliveryMenHelper()->getDataById2($acceptance[getAcceptanceHelper()->deliveryManId]);
    //   $user = getUsersHelper()->getDataById($userId);
    //   $acceptance["deliveryMan"] = $deliveryMan;
    // }

    $orderDelivery['acceptance'] = $acceptance;
    return $orderDelivery;
    // return $data;
  }
  function executeGetUncollectedOrders($deliveryManId)
  {
    $data = getOrdersDeliveryHelper()->getDataUncollected($deliveryManId);
    return $data;
  }
  function executeUpdateActualPrice($id, $newValue, $managerId)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $orderDelivery = getOrdersDeliveryHelper()->getDataById($id);
    $order = getOrdersHelper()->getDataById($orderDelivery[getOrdersDeliveryHelper()->orderId]);
    checkOrderOwner($order, $managerId);

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
  function executeUpdatePrice($id, $newValue, $managerId)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $orderDelivery = getOrdersDeliveryHelper()->getDataById($id);
    $order = getOrdersHelper()->getDataById($orderDelivery[getOrdersDeliveryHelper()->orderId]);
    checkOrderOwner($order, $managerId);

    // 
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    // 
    getOrdersDeliveryHelper()->updatePrice($id, $newValue);
    $data = getOrdersDeliveryHelper()->getDataById($id);
    shared_execute_sql("COMMIT");
    return $data;
  }
  function executeUserLocation($id, $newValue, $managerId)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $orderDelivery = getOrdersDeliveryHelper()->getDataById($id);
    $order = getOrdersHelper()->getDataById($orderDelivery[getOrdersDeliveryHelper()->orderId]);
    checkOrderOwner($order, $managerId);

    // 
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    // 
    getOrdersDeliveryHelper()->updateUserLocationId($id, $newValue);
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
  function executeAddData($orderId, $amount, $type, $managerId)
  {
    shared_execute_sql("START TRANSACTION");
    $order = getOrdersHelper()->getDataById($orderId);
    checkOrderOwner($order, $managerId);

    // 
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    $id = uniqid(rand(), false);
    $dataAfterAdd = getOrdersDiscountsHelper()->addData($id, $orderId, $amount, $type);
    shared_execute_sql("COMMIT");

    return $dataAfterAdd;
  }
  function executeUpdateType($orderId, $id, $type, $managerId)
  {
    shared_execute_sql("START TRANSACTION");
    $order = getOrdersHelper()->getDataById($orderId);
    checkOrderOwner($order, $managerId);
    // 
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    $updatedData = getOrdersDiscountsHelper()->updateType($id, $type);
    shared_execute_sql("COMMIT");
    return $updatedData;
  }
  function executeUpdateAmount($orderId, $id, $amount, $managerId)
  {
    shared_execute_sql("START TRANSACTION");
    $order = getOrdersHelper()->getDataById($orderId);
    checkOrderOwner($order, $managerId);

    // 
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    $updatedData = getOrdersDiscountsHelper()->updateAmount($id, $amount);
    shared_execute_sql("COMMIT");
    return $updatedData;
  }
  function executeDeleteData($orderId, $orderDiscountId, $managerId)
  {
    shared_execute_sql("START TRANSACTION");
    $order = getOrdersHelper()->getDataById($orderId);
    checkOrderOwner($order, $managerId);
    getOrdersDiscountsHelper()->deleteData($orderDiscountId);
    shared_execute_sql("COMMIT");
    return;
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


// SHARED
function checkOrderOwner($order, $managerId)
{
  if ($order[getOrdersHelper()->managerId] != null) {
    if ($order[getOrdersHelper()->managerId] != $managerId) {
      $ar = "هذا الطلب تم تعيينه لكاشيير اخر";
      $en = "هذا الطلب تم تعيينه لكاشيير اخر";
      exitFromScript($ar, $en);
    }
  }
}
