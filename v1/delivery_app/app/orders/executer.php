<?php
namespace DeliveryMen;

require_once ('helper.php');
class OrdersExecuter
{
  function executeOrderInRoad($orderId)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $order = getOrdersHelper()->getDataById($orderId);
    $situatinId = getOrdersHelper()->ORDER_IN_ROAD;
    getOrdersHelper()->updateStatus(getId($order), $situatinId);
    getOrdersStatusHelper()->addData(getId($order), $situatinId);
    getOrdersHelper()->updateCode($orderId, rand(1001, 9998));
    shared_execute_sql("COMMIT");
    return ["success" => "false"];
  }
  function executeCheckCode($deliveryManId, $orderId, $code)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $order = getOrdersHelper()->getDataById($orderId);
    if ($order[getOrdersHelper()->code] != $code) {
      $ar = "الكود غير صحيح";
      $en = "الكود غير صحيح";
      exitFromScript($ar, $en);
    }
    $situatinId = getOrdersHelper()->ORDER_COMPLETED;
    getOrdersHelper()->updateStatus(getId($order), $situatinId);
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
        $sum = $sum - $discount;
      } else {
        $sum = $sum - $amount;
      }
    }
    require_once __DIR__ . '/../collections/helper.php';
    getCollectionsHelper()->addData($orderId, $deliveryManId, $sum);
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