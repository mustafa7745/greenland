<?php
namespace Manager;

require_once('sql.php');
// 
class OrdersHelper extends OrdersSql
{
  function checkIfhaveOrderNotComplete($userId)
  {
    $sql = $this->readSituationSql("'$userId'");
    // print_r($sql);
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) > 0) {
      $ar = "يوجد طلب تحت المعالجة";
      $en = "USER_HAVE_INCOMPLETE_ORDER_BEFORE";
      exitFromScript($ar, $en);
    }
  }
  function addOrder($userId)
  {
    $sql = $this->addSql("'$userId'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_ORDER";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_ORDER";
      exitFromScript($ar, $en);
    }
  }
  function getDataById($orderId)
  {
    $sql = $this->readByIdSql("'$orderId'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "ORDER_ID_ERROR";
      $en = "ORDER_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getNotComplete()
  {
    $sql = $this->readNotCompleteSql();
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getNotCompleteCount()
  {
    $sql = $this->readNotCompleteSql();
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getDataByUserId($userId)
  {
    $sql = $this->readByUserIdSql("'$userId'");
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function searchDataById($id)
  {
    $sql = $this->searchSql("'$id'");
    // print_r($sql);
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "لايوجد طلب بهذا الرقم";
      $en = "ORDER_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getData($orderStatusIds, $managerId)
  {
    $sql = $this->readSql($orderStatusIds, "'$managerId'");
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getDataByStatusId($orderStatusId, $managerId)
  {
    $sql = $this->readByStatusIdSql("'$orderStatusId'", "'$managerId'");
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function updateStatus($id, $newValue)
  {

    $sql = $this->updateStatusSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
  }
  function updateManagerId($id, $newValue)
  {
    $sql = $this->updateManagerIdSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
  }

  function updateSystemOrderNumber($id, $newValue)
  {

    $sql = $this->updateSystemOrderNumberSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
  }
}
$orders_helper = null;
function getOrdersHelper()
{
  global $orders_helper;
  if ($orders_helper == null) {
    return (new OrdersHelper());
  }
  return $orders_helper;
}
/********/

class OrdersProductsHelper extends OrdersProductsSql
{
  function addOrderProducts($orderId, $productId, $productName, $productPrice, $productQuantity)
  {
    $sql = $this->addSql("'$orderId'", "'$productId'", "'$productName'", "'$productPrice'", "'$productQuantity'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED";
      $en = "DATA_NOT_EFFECTED";
      exitFromScript($ar, $en);
    }
  }
  function getOrderProductsByOrderId($orderId)
  {
    $sql = $this->readByOrderIdSql("'$orderId'");
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getOrderProductsByOrderId2($orderId)
  {
    $sql = $this->readByOrderId2Sql("'$orderId'");
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getDataByOrderIdAndProductId($orderId, $productId)
  {
    $sql = $this->readByOrderIdAndProductIdSql("'$orderId'", "'$productId'");
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }

  function updateQuantity($id, $newValue)
  {

    $sql = $this->updateQuantitySql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
  }

  function deleteData($ids, $count)
  {
    $sql = $this->deleteSql($ids);
    // print_r($sql);
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != $count) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_DELETE_";
      $en = "DATA_NOT_EFFECTED_WHEN_DELETE_";
      exitFromScript($ar, $en);
    }
  }
  function getDataById($id)
  {
    $sql = $this->readByIdSql("'$id'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "ORDER_P_ID_ERROR";
      $en = "ORDER_P_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getDataByOrderId($orderId)
  {
    $sql = $this->readByOrderId3Sql("'$orderId'");
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
}
$orders_products_helper = null;
function getOrdersProductsHelper()
{
  global $orders_products_helper;
  if ($orders_products_helper == null) {
    $orders_products_helper = (new OrdersProductsHelper());
  }
  return $orders_products_helper;
}
/********/

// ==========//
class OrdersOffersHelper extends OrdersOffersSql
{
  function addOrderOffers($id, $orderId, $offerName, $offerId, $offerQuantity, $offerPrice)
  {
    $sql = $this->addSql("'$id'", "'$orderId'", "'$offerName'", "'$offerId'", "'$offerQuantity'", "'$offerPrice'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED";
      $en = "DATA_NOT_EFFECTED";
      exitFromScript($ar, $en);
    }
  }
  function getDataByOrderId($orderId)
  {
    $sql = $this->readByOrderIdSql("'$orderId'");
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }

  function updateQuantity($id, $newValue)
  {
    $sql = $this->updateQuantitySql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
  }

  function deleteData($ids, $count)
  {
    $sql = $this->deleteSql($ids);
    // print_r($sql);
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != $count) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_DELETE_";
      $en = "DATA_NOT_EFFECTED_WHEN_DELETE_";
      exitFromScript($ar, $en);
    }
  }
  function getDataById($id)
  {
    $sql = $this->readByIdSql("'$id'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "ORDER_P_ID_ERROR";
      $en = "ORDER_P_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getDataByOrderIdAndOfferId($orderId, $offerId)
  {
    $sql = $this->readByOrderIdAndOfferIdSql("'$orderId'", "'$offerId'");
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
}
$orders_offers_helper = null;
function getOrdersOffersHelper()
{
  global $orders_offers_helper;
  if ($orders_offers_helper == null) {
    $orders_offers_helper = (new OrdersOffersHelper());
  }
  return $orders_offers_helper;
}
// =========//

class OrdersStatusHelper extends OrdersStatusSql
{
  function addData($orderId, $situationId)
  {
    $sql = $this->addSql("'$orderId'", "'$situationId'");
    // print_r($sql); 
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_ORDER_S";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_ORDER";
      exitFromScript($ar, $en);
    }
  }
  // 
  function getOrderStatus($order_id)
  {
    $sql = $this->readByOrderIdSql("'$order_id'");
    // print_r($sql);
    // $this->initJson();
    $data = shared_execute_read1_no_json_sql($sql);
    // print_r($data);
    return $data;
  }

}
$orders_status_helper = null;
function getOrdersStatusHelper()
{
  global $orders_status_helper;
  if ($orders_status_helper == null) {
    $orders_status_helper = (new OrdersStatusHelper());
  }
  return $orders_status_helper;
}
// 
class OrdersDeliveryHelper extends OrdersDeliverySql
{
  function getDataById($id)
  {
    $sql = $this->readByIdSql("'$id'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "ID_ERROR";
      $en = "ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getDataByOrderIdsAndDeliveryManIds($orderIds, $deliveryManIds)
  {
    $sql = $this->readByOrderIdsAndDeliveryManIdsSql($orderIds, $deliveryManIds);
    // print_r($sql);
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getDataByOrderId($orderId)
  {
    $sql = $this->readByOrderIdSql("'$orderId'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) == 0) {
      $ar = "لايوجد بيانات توصيل في هذا الطلب";
      $en = "لايوجد بيانات توصيل في هذا الطلب";
      exitFromScript($ar, $en);
    }
    if (count($data) > 1) {
      $ar = "هناك ارقام اوردرات متشابهه يرجى الاتصال بالمسؤوول";
      $en = "هناك ارقام اوردرات متشابهه يرجى الاتصال بالمسؤوول";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getDataByOrderId2($orderId)
  {
    $sql = $this->readByOrderIdSql("'$orderId'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      return null;
    }
    return $data[0];
  }
  function addData($id, $orderId, $price, $actualPrice, $userLocationId, $deliveryManId)
  {
    $sql = $this->addSql("'$id'", "'$orderId'", "'$price'", "'$actualPrice'", "'$userLocationId'", "'$deliveryManId'");
    // print_r($sql); 
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_ORDER";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_ORDER";
      exitFromScript($ar, $en);
    }
  }
  function checkIfhaveOrderDelivery($orderId)
  {
    $sql = $this->readByOrderIdSql("'$orderId'");
    // print_r($sql);
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) > 0) {
      return $data[0];
    }
    return null;
  }
  function updateDeliveryManId($id, $newValue)
  {
    $sql = $this->updateDeliveryManIdSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      exitFromScript($ar, $en);
    }
  }
  function updateUserLocationId($id, $newValue)
  {
    $sql = $this->updateUserLocationIdSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      exitFromScript($ar, $en);
    }
  }
  function updateActualPrice($id, $newValue)
  {

    $sql = $this->updateActualPriceSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
  }
  function updatePrice($id, $newValue)
  {

    $sql = $this->updatePriceSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
  }
  function updateBothPrice($id, $newValue)
  {

    $sql = $this->updateBothPriceSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
  }
}
$orders_delivery_helper = null;
function getOrdersDeliveryHelper()
{
  global $orders_delivery_helper;
  if ($orders_delivery_helper == null) {
    $orders_delivery_helper = (new OrdersDeliveryHelper());
  }
  return $orders_delivery_helper;
}

class OrdersDiscountsHelper extends OrdersDiscountsSql
{
  function getDataById($id)
  {
    $sql = $this->readByIdSql("'$id'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "ORDER_ID_ERROR";
      $en = "ORDER_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getDataByOrderId($orderId)
  {
    $sql = $this->readByOrderIdSql("'$orderId'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      return null;
    }
    return $data[0];
  }
  function addData($id, $orderId, $amount, $type)
  {
    $sql = $this->addSql("'$id'", "'$orderId'", "'$amount'", "'$type'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_D_ORDER";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_ORDER";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateType($id, $newValue)
  {
    $sql = $this->updateTypeSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateAmount($id, $newValue)
  {
    $sql = $this->updateAmountSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function deleteData($id)
  {
    $sql = $this->deleteSql("'$id'");
    // print_r($sql);
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_DELETE_";
      $en = "DATA_NOT_EFFECTED_WHEN_DELETE_";
      exitFromScript($ar, $en);
    }

  }
}
$orders_discount_helper = null;
function getOrdersDiscountsHelper()
{
  global $orders_discount_helper;
  if ($orders_discount_helper == null) {
    $orders_discount_helper = new OrdersDiscountsHelper();
  }
  return $orders_discount_helper;
}