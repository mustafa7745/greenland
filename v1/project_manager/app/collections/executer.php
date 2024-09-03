<?php
namespace Manager;

require_once 'helper.php';
class CollectionsExecuter
{
  function executeGetData($deliveryManId)
  {
    require_once __DIR__ . '/../../app/orders/executer.php';
    $orderExecuter = getOrdersExecuter();
    $collections = getCollectionsHelper()->getData($deliveryManId);
    for ($i = 0; $i < count($collections); $i++) {
      $sum = 0;
      $orderId = $collections[$i][getCollectionsHelper()->orderId];
      require_once __DIR__ . '/../../../include/shared_app/order-content/index.php';
      $data = (new \OrderContent());
      $data->executeGetData($orderId);

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
          // print_r($amount);
          $discount = ($sum * $amount) / 100;
          // print_r($discount);
          // print_r($sum);


          $sum = $sum - $discount;
        } else {
          $sum = $sum - $amount;
        }
      }
      // $collections[$i]['price'] = $orderExecuter->executeGetFinalOrderPriceWithoutDeliveryPrice($orderId);
      $collections[$i]['price'] = $sum;
    }
    return $collections;
  }
  function executeCollectData($ids)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $idsString = convertIdsListToStringSql($ids);
    getCollectionsHelper()->getDataByIds($idsString);
    getCollectionsHelper()->updateIsCollect($idsString, count($ids));
    // 
    shared_execute_sql("COMMIT");

    return ['success', 'true'];


  }
}

$collections_executer = null;
function getCollectionsExecuter()
{
  global $collections_executer;
  if ($collections_executer == null) {
    $collections_executer = new CollectionsExecuter();
  }
  return $collections_executer;
}