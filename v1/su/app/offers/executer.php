<?php
namespace SU1;

require_once 'helper.php';
class OffersExecuter
{
  function executeGetData()
  {
    return getOffersHelper()->getData();
  }
  function executeAddData($name, $description, $price)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $id = uniqid(rand(), false);
    $dataAfterAdd = getOffersHelper()->addData($id, $name, $description, $price);
    shared_execute_sql("COMMIT");
    return $dataAfterAdd;
  }

  function executeUpdateName($id, $newValue)
  {
    shared_execute_sql("START TRANSACTION");
    getOffersHelper()->getDataById($id);
    $dataAfterUpdate = getOffersHelper()->updateName($id, $newValue);
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateDescription($id, $newValue)
  {
    shared_execute_sql("START TRANSACTION");
    getOffersHelper()->getDataById($id);
    $dataAfterUpdate = getOffersHelper()->updateDescription($id, $newValue);
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdatePrice($id, $newValue)
  {
    shared_execute_sql("START TRANSACTION");
    getOffersHelper()->getDataById($id);
    $dataAfterUpdate = getOffersHelper()->updatePrice($id, $newValue);
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }

}

$offers_executer = null;
function getOffersExecuter()
{
  global $offers_executer;
  if ($offers_executer == null) {
    $offers_executer = new OffersExecuter();
  }
  return $offers_executer;
}