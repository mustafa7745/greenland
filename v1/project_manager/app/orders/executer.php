<?php
namespace Manager;


require_once('sql.php');
require_once('helper.php');
class OrdersExecuter extends OrdersSql
{
  function executeAddData($userId, $order_products, $userLocationId, $deliveryManId, $price, $actualPrice, $managerId)
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

    getOrdersHelper()->addOrder($userId);
    $orderId = getDB()->conn->insert_id;
    getOrdersHelper()->updateManagerId($orderId, $managerId);

    $situatinId = getOrdersHelper()->ORDER_VIEWD;
    getOrdersHelper()->updateStatus($orderId, $situatinId);
    getOrdersStatusHelper()->addData($orderId, $situatinId);


    $final_sum = 0.0;
    for ($i = 0; $i < count($products); $i++) {
      $productId = $products[$i][getProductsHelper()->id];
      $productName = $products[$i][getProductsHelper()->name];
      $productPrice = $products[$i][getProductsHelper()->postPrice];
      $final_sum = $final_sum + $productPrice;
      $productQuantity = getQntFromOrderProducts($order_products, $productId);
      getOrdersProductsHelper()->addOrderProducts($orderId, $productId, $productName, $productPrice, $productQuantity);
    }

    $orderDeliveryId = uniqid(rand(), false);
    getOrdersDeliveryHelper()->addData($orderDeliveryId, $orderId, $price, $actualPrice, $userLocationId, $deliveryManId);

    $situatinId = getOrdersHelper()->ORDER_ASSIGNED_DELIVERY_MAN;
    getOrdersHelper()->updateStatus($orderId, $situatinId);
    getOrdersStatusHelper()->addData($orderId, $situatinId);

    require_once __DIR__ . '/../../../include/shared_app/order-content/index.php';
    $data = (new \OrderContent());
    $data->executeGetData($orderId);
    shared_execute_sql("COMMIT");
    require_once __DIR__ . "/../delivery_men/executer.php";
    getDeliveryMenExecuter()->sendMessageToDeliveryMan($deliveryManId, "تم اضافة طلب يرجى متابعته");
    return $data;
  }
  function executeAddDataWithoutDelivery($userId, $order_products, $managerId)
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
    getOrdersHelper()->addOrder($userId);
    $orderId = getDB()->conn->insert_id;
    // 
    getOrdersHelper()->updateManagerId($orderId, $managerId);

    $final_sum = 0.0;
    for ($i = 0; $i < count($products); $i++) {
      $productId = $products[$i][getProductsHelper()->id];
      $productName = $products[$i][getProductsHelper()->name];
      $productPrice = $products[$i][getProductsHelper()->postPrice];
      $final_sum = $final_sum + $productPrice;
      $productQuantity = getQntFromOrderProducts($order_products, $productId);
      getOrdersProductsHelper()->addOrderProducts($orderId, $productId, $productName, $productPrice, $productQuantity);
    }

    $situatinId = getOrdersHelper()->ORDER_COMPLETED;
    getOrdersHelper()->updateStatus($orderId, $situatinId);
    getOrdersStatusHelper()->addData($orderId, $situatinId);

    require_once __DIR__ . '/../../../include/shared_app/order-content/index.php';
    $data = (new \OrderContent());
    $data->executeGetData($orderId);
    shared_execute_sql("COMMIT");
    return $data;

  }
  /********/
  function executeGetData($orderStatusId, $managerId)
  {
    $helper = getOrdersHelper();
    $status = [$helper->ORDER_STARTED, $helper->ORDER_VIEWD, $helper->ORDER_ASSIGNED_DELIVERY_MAN, $helper->ORDER_PREPARING, $helper->ORDER_IN_ROAD];
    $data = [];
    if (in_array($orderStatusId, $status)) {
      $data = getOrdersHelper()->getDataByStatusId($orderStatusId, $managerId);
    } else {
      array_push($status, $helper->ORDER_COMPLETED, $helper->ORDER_CENCELED);
      $statusIds = convertIdsListToStringSql($status);
      $data = getOrdersHelper()->getData($statusIds, $managerId);
    }

    return $data;
  }
  // 
  function executeGetPendingData()
  {
    $helper = getOrdersHelper();
    $count = $helper->getNotCompleteCount()[0]['count(*)'];
    return ['count' => $count, 'date' => getCurruntDate()];
  }

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
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }

    getOrdersHelper()->updateSystemOrderNumber($orderId, $systemOrderNumber);
    if ($order[getOrdersHelper()->systemOrderNumber] == null) {
      $situatinId = getOrdersHelper()->ORDER_PREPARING;
      getOrdersHelper()->updateStatus($orderId, $situatinId);
      getOrdersStatusHelper()->addData($orderId, $situatinId);
    }

    // $data = getOrdersHelper()->getData();
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    // $order = getOrdersHelper()->getDataById($orderId);
    if ($order[getOrdersHelper()->systemOrderNumber] == null) {
      $userId = $order[getOrdersHelper()->userId];
      global $USER_ANDROID_APP;

      sendMessage($userId, "يتم الان تجهيز طلبك", $USER_ANDROID_APP);
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

    getOrdersProductsHelper()->addOrderProducts($orderId, $productId, $productName, $productPrice, $productQuantity);
    $id = getDB()->conn->insert_id;
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
    // if ($order[getOrdersHelper()->managerId] == null) {
    //   getOrdersHelper()->updateManagerId($order[getOrdersHelper()->id], $managerId);
    // }

    // getOrdersHelper()->updateManagerId($order[getOrdersHelper()->id], $managerId);


    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    $situationId = getOrdersHelper()->ORDER_CENCELED;
    getOrdersHelper()->updateStatus($orderId, $situationId);
    getOrdersStatusHelper()->addData($orderId, $situationId);
    getOrdersCenceledHelper()->addData($orderId, $description);

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
      if ($order[getOrdersHelper()->managerId] == null) {
        getOrdersHelper()->updateManagerId($order[getOrdersHelper()->id], $managerId);
      }
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
  function executeGetData($orderId, $managerId)
  {
    shared_execute_sql("START TRANSACTION");
    $orderDelivery = getOrdersDeliveryHelper()->getDataByOrderId($orderId);
    $order = getOrdersHelper()->getDataById($orderDelivery[getOrdersDeliveryHelper()->orderId]);
    checkOrderOwner($order, $managerId);

    shared_execute_sql("COMMIT");
    // $orderDelivery['acceptance'] = $acceptance;
    return $orderDelivery;
    // return $data;
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
    $userLocationId = $newValue;
    require_once __DIR__ . '/../users_locations/helper.php';
    $userLocation = getUsersLocationsHelper()->getDataById($userLocationId);
    $price = getDeliveryPrice($userLocation);
    getOrdersDeliveryHelper()->updateBothPrice($id, $price);

    $data = getOrdersDeliveryHelper()->getDataById($id);
    shared_execute_sql("COMMIT");
    return $data;
  }
  function executeAssignOrderToDeliveryMan($id, $userSessionId, $deliveryManId, $managerId)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $orderDelivery = getOrdersDeliveryHelper()->getDataById($id);
    $orderId = $orderDelivery[getOrdersDeliveryHelper()->orderId];
    $order = getOrdersHelper()->getDataById($orderId);
    checkOrderOwner($order, $managerId);

    // 
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    if ($deliveryManId == $orderDelivery[getOrdersDeliveryHelper()->deliveryManId]) {
      $ar = "هذا الطلب له بالفعل";
      $en = "هذا الطلب له بالفعل";
      exitFromScript($ar, $en);
    }
    // 
    getOrdersDeliveryHelper()->updateDeliveryManId($id, $deliveryManId);
    $situatinId = getOrdersHelper()->ORDER_ASSIGNED_DELIVERY_MAN;
    getOrdersHelper()->updateStatus($orderId, $situatinId);
    getOrdersStatusHelper()->addData($orderId, $situatinId);
    // 
    $data = getOrdersDeliveryHelper()->getDataById($id);
    shared_execute_sql("COMMIT");
    require_once __DIR__ . "/../delivery_men/executer.php";
    getDeliveryMenExecuter()->sendMessageToDeliveryMan($deliveryManId, "تم اضافة طلب يرجى متابعته");

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
  } else {
    getOrdersHelper()->updateManagerId($order[getOrdersHelper()->id], $managerId);
  }
}

function sendMessage($userId, $body, $appId)
{
  require_once __DIR__ . '/../../app/users/helper.php';
  $user = getUsersHelper()->getDataById($userId);
  require_once __DIR__ . '/../../../include/users_sessions_devices_sessions/helper.php';
  $token = getUsersSessionsHelper()->getToken($userId, $appId);
  if ($token != null) {
    require_once __DIR__ . '/../../../include/projects/helper.php';
    $project = getProjectsHelper()->getDataById(1);
    require_once __DIR__ . '/../../../include/send_message.php';
    $title = "مرحبا بك: " . $user[getUsersHelper()->name];
    sendMessageToOne($project[getProjectsHelper()->serviceAccountKey], $token, $title, $body);
  }
}