<?php
namespace UserApp;

require_once ('sql.php');
// 
class OrdersHelper extends OrdersSql
{
  function checkIfhaveOrderNotComplete($userId)
  {
    $sql = $this->readSituationSql("'$userId'");
    // print_r($sql);
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) > 0) {
      $ar = "يوجد طلب معلق";
      $en = "USER_HAVE_INCOMPLETE_ORDER_BEFORE";
      exitFromScript($ar, $en);
    }
  }
  function addOrder($id, $userId)
  {
    $sql = $this->addSql("'$id'", "'$userId'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_ORDER";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_ORDER";
      exitFromScript($ar, $en);
    }
  }
  function getOrder($order_id)
  {
    $sql = $this->read_by_id_sql("'$order_id'");
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
  function getOrderProductsByOrderWithItsStuff1($order_id)
  {

    $orderProducts = $this->getOrderProductsByOrderId($order_id);


    $final_price = 0;
    for ($i = 0; $i < count($orderProducts); $i++) {
      $final_price = $final_price + $orderProducts[$i]["avg"];
    }
    // print_r($final_price);
    $products_final_price = $final_price;

    // 
    $delivery = getOrdersDeliveryHelper()->checkIfhaveOrderDelivery($order_id);
    $isDeliveryWithOrder = null;
    $delivery_price = 0;
    if ($delivery != null) {
      $delivery["price"] = intval($delivery["price"]);
      $delivery_price = getPrice($delivery);
      $final_price = $final_price + $delivery_price;

      // $orderDeliveryGetter = getOrdersDeliveryGetter($delivery);
      $s = getIsWithOrder($delivery);
      if ($s == "1") {
        $isDeliveryWithOrder = $s;
      }
    }
    // $discount = getOrdersDiscountsHelper()->checkIfhaveOrderDiscount($order_id);
    // if ($discount != null) {
    //   $ordersDiscountsGetter = getOrdersDiscountsGetter($discount);
    //   // 
    //   $amount = $ordersDiscountsGetter->getOrderDiscountAmount();
    //   $discount_type = $ordersDiscountsGetter->getOrderDiscountType();
    //   if ($isDeliveryWithOrder != null) {

    //     if ($discount_type == "1") {
    //       $final_price = $final_price - ($final_price * $amount / 100);
    //     } else {
    //       $final_price = $final_price - $amount;
    //     }
    //   } else {
    //     $final_price = $final_price - $delivery_price;

    //     if ($discount_type == "1") {
    //       $final_price = $final_price - ($products_final_price * $amount / 100);
    //     } else {
    //       $final_price = $products_final_price - $amount;
    //     }
    //   }

    // }
    // $final_price = ($final_price);
    // unset($project_currency["currency_id"]);
    $r = array("orderId" => $order_id, "products" => $orderProducts, "delivery" => $delivery, 'discount' => null, "productsFinalPrice" => $products_final_price, "finalPrice" => $final_price);
    return $r;
  }
  function getOrderProductsByOrderWithItsStuff($order)
  {
    // 
    $ordersGetter = getOrdersGetter($order);
    $order_id = $ordersGetter->getOrderId();
    $orderProducts = $this->getOrderProductsByOrderId($order_id);
    $currency_id = $ordersGetter->getCurrencyId();
    require_once (getPath() . "app/user_app/currencies/helper.php");
    $currency = getProjectsCurrenciesHelper()->ge($currency_id);
    $final_price = 0.0;
    for ($i = 0; $i < count($orderProducts); $i++) {
      $final_price = $final_price + $orderProducts[$i]["avg"];
    }
    $products_final_price = $final_price;

    // 
    $delivery = getOrdersDeliveryHelper()->checkIfhaveOrderDelivery($order_id);
    $isDeliveryWithOrder = null;
    $delivery_price = 0;
    if ($delivery != null) {
      $delivery_price = getOrdersDeliveryGetter($delivery)->getOrderDeliveryPrice();
      $final_price = $final_price + $delivery_price;

      $orderDeliveryGetter = getOrdersDeliveryGetter($delivery);
      $s = $orderDeliveryGetter->getOrderDeliveryIsWithOrder();
      if ($s == "1") {
        $isDeliveryWithOrder = $s;
      }
    }
    $discount = getOrdersDiscountsHelper()->checkIfhaveOrderDiscount($order_id);
    if ($discount != null) {
      $ordersDiscountsGetter = getOrdersDiscountsGetter($discount);
      // 
      $amount = $ordersDiscountsGetter->getOrderDiscountAmount();
      $discount_type = $ordersDiscountsGetter->getOrderDiscountType();
      if ($isDeliveryWithOrder != null) {

        if ($discount_type == "1") {
          $final_price = $final_price - ($final_price * $amount / 100);
        } else {
          $final_price = $final_price - $amount;
        }
      } else {
        $final_price = $final_price - $delivery_price;

        if ($discount_type == "1") {
          $final_price = $final_price - ($products_final_price * $amount / 100);
        } else {
          $final_price = $products_final_price - $amount;
        }
      }

    }
    $final_price = round($final_price);
    $r = array("order_id" => $order_id, "products" => $orderProducts, "currency" => $currency, "discount" => $discount, "delivery" => $delivery, "products_final_price" => $products_final_price, "final_price" => $final_price, );
    return $r;
  }
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

  function addData($id, $orderId, $price, $userLocationId)
  {
    $sql = $this->addSql($id, "'$orderId'", "'$price'", "'$userLocationId'");
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