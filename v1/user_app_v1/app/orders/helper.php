<?php
namespace UserApp;

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
  function getOrder($userId, $orderId)
  {
    $sql = $this->read_by_id_and_userId_sql("'$userId'", "'$orderId'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "ORDER_ID_ERROR";
      $en = "ORDER_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getData($userId, $offset)
  {
    $sql = $this->readSql("'$userId'", $offset);
    // print_r($sql);
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
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
class OrdersStatusHelper extends OrdersStatusSql
{

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

  function addData($id, $orderId, $price, $actualPrice, $userLocationId)
  {
    $sql = $this->addSql("'$id'", "'$orderId'", "'$actualPrice'", "'$price'", "'$userLocationId'");
    // print_r($sql); 
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_ORDER";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_ORDER";
      exitFromScript($ar, $en);
    }
  }
  function getDataByOrderId($orderId)
  {
    $sql = $this->readByOrderIdSql("'$orderId'");
    // print_r($sql);
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      return null;
    }
    return $data[0];

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
// 
class OrdersOffersHelper extends OrdersOffersSql
{

  function addOrderOffer($orderId, $productId, $productName, $productPrice, $productQuantity)
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

}
$orders_offers_helper = null;
function getOrdersOffersHelper()
{
  global $orders_offers_helper;
  if ($orders_offers_helper == null) {
    $orders_offers_helper = new OrdersOffersHelper();
  }
  return $orders_offers_helper;
}