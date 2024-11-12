<?php
namespace DeliveryMen;

require_once('helper.php');
class OrdersExecuter
{

  function executeGetData($deliveryManId)
  {
    return getOrdersDeliveryHelper()->getDataByDeliveryManId($deliveryManId);
  }
  function executeGetOrderContent($orderId)
  {
    require_once __DIR__ . '/../../../include/shared_app/order-content/index.php';
    $data = (new \OrderContent());
    $data->executeGetData($orderId);
    return $data;
  }
  function executeOrderInRoad($orderId, $deliveryManId)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $order = getOrdersHelper()->getDataById($orderId);
    $this->checkOwner($orderId, $deliveryManId);

    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }

    if ($order[getOrdersHelper()->systemOrderNumber] == null) {
      $ar = "يجب تحديد رقم الفاتورة";
      $en = "يجب تحديد رقم الفاتورة";
      exitFromScript($ar, $en);
    }
    if ($order[getOrdersHelper()->code] != null) {
      $ar = "تم ارسال الطلب في الطريق من قبل";
      $en = "تم ارسال الطلب في الطريق من قبل";
      exitFromScript($ar, $en);
    }

    $situatinId = getOrdersHelper()->ORDER_IN_ROAD;
    getOrdersHelper()->updateStatus(getId($order), $situatinId);
    getOrdersStatusHelper()->addData(getId($order), $situatinId);
    getOrdersHelper()->updateCode($orderId, rand(1001, 9998));
    $dateAfterUpdate = getOrdersDeliveryHelper()->getDataByOrderId2($orderId);
    shared_execute_sql("COMMIT");
    return $dateAfterUpdate;
    ;
  }

  function checkOwner($orderId, $deliveryManId)
  {
    require_once __DIR__ . "/../orders/helper.php";
    $orderDelivery = getOrdersDeliveryHelper()->getDataByOrderId($orderId);
    if ($orderDelivery[getOrdersDeliveryHelper()->deliveryManId] != $deliveryManId) {
      $ar = "هذا الطلب ليس مسجل لديك";
      $en = "هذا الطلب ليس مسجل لديك";
      exitFromScript($ar, $en);
    }
  }
  function executeCheckCode($deliveryManId, $orderId, $code)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $order = getOrdersHelper()->getDataById($orderId);
    $this->checkOwner($orderId, $deliveryManId);
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }

    if ($order[getOrdersHelper()->code] == null) {
      $ar = "لم يتم تفعيل الطلب في الطريق";
      $en = "لم يتم تفعيل الطلب في الطريق";
      exitFromScript($ar, $en);
    }
    if ($order[getOrdersHelper()->code] != $code) {
      $ar = "الكود غير صحيح";
      $en = "الكود غير صحيح";
      exitFromScript($ar, $en);
    }
    $situatinId = getOrdersHelper()->ORDER_COMPLETED;
    getOrdersHelper()->updateStatusWithPaid(getId($order), $situatinId, getOrdersHelper()->PAID_ON_DELIVERY);
    getOrdersStatusHelper()->addData(getId($order), $situatinId);
    // 
    require_once __DIR__ . '/../../../include/shared_app/order-content/index.php';
    $data = (new \OrderContent());
    $data->executeGetData($orderId);

    $sum = 0;

    foreach ($data->products as $key => $value) {
      $sum = $sum + ($value['productPrice'] * $value['productQuantity']);
    }
    foreach ($data->offers as $key => $value) {
      $sum = $sum + ($value['offerPrice'] * $value['offerQuantity']);
    }
    if ($data->discount != null) {
      require_once __DIR__ . '/../orders/helper.php';
      $helper = getOrdersDiscountsHelper();
      $amount = $data->discount[$helper->amount];
      $type = $data->discount[$helper->type];

      if ($type == $helper->PERCENTAGE_TYPE) {
        $discount = ($sum * $amount) / 100;
        $sum = $sum - (100 * round($discount / 100));
      } else {
        $sum = $sum - $amount;
      }
    }
    require_once __DIR__ . '/../orders/helper.php';
    $sum = $sum + $data->delivery[getOrdersDeliveryHelper()->price];
    $sum = $sum - $data->delivery[getOrdersDeliveryHelper()->actualPrice];

    $managerId = $order[getOrdersHelper()->managerId];
    require_once __DIR__ . '/../collections/helper.php';
    getCollectionsHelper()->addData($orderId, $deliveryManId, $managerId, $sum);
    shared_execute_sql("COMMIT");
    return ["success" => "false"];
  }

}
$orders_executer = null;
function getOrdersExecuter()
{
  global $orders_executer;
  if ($orders_executer == null) {
    $orders_executer = new OrdersExecuter();
  }
  return $orders_executer;
}