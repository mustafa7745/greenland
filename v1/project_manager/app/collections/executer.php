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
      $orderId = $collections[$i][getCollectionsHelper()->orderId];
      $collections[$i]['price'] = $orderExecuter->executeGetFinalOrderPriceWithoutDeliveryPrice($orderId);
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