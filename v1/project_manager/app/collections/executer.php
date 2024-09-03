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
    $sum = 0;
    for ($i = 0; $i < count($collections); $i++) {
      $orderId = $collections[$i][getCollectionsHelper()->orderId];
      require_once __DIR__ . '/../../../include/shared_app/order-content/index.php';
      $data = (new \OrderContent());
      $data->executeGetData($orderId);

      foreach ($data->products as $key => $value) {
        $sum = $sum + $value['productPrice'];
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