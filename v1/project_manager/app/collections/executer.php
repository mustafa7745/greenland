<?php
namespace Manager;

require_once 'helper.php';
class CollectionsExecuter
{
  function executeGetData($deliveryManId, $managerId)
  {
    require_once __DIR__ . '/../../app/orders/executer.php';
    $orderExecuter = getOrdersExecuter();
    $collections = getCollectionsHelper()->getData($deliveryManId, $managerId);
    return $collections;
  }
  function executeCollectData($ids, $managerId)
  {
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $idsString = convertIdsListToStringSql($ids);
    getCollectionsHelper()->getDataByIds($idsString, $managerId);
    getCollectionsHelper()->updateIsCollect($idsString, count($ids), $managerId);
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