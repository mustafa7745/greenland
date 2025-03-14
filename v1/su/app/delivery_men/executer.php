<?php
namespace SU1;

use function SU1\getUsersHelper;

require_once 'helper.php';
class DeliveryMenExecuter
{
  function executeGetData($userId)
  {
    $data = getDeliveryMenHelper()->getData($userId);
    if (count($data) == 1) {
      return $data[0];
    }
    return null;
  }

  function executeAddData($userId)
  {
    $helper = getDeliveryMenHelper();
    require_once __DIR__ . '/../../app/users/helper.php';

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    getUsersHelper()->getDataById($userId);
    $id = uniqid(rand(), false);
    $dataAfterAdd = $helper->addData($id, $userId);
    shared_execute_sql("COMMIT");
    return $dataAfterAdd;
  }

  function executeUpdateStatus($id)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    $dataAfterUpdate = getDeliveryMenHelper()->updateStatus($id);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
}

$delivery_men_executer = null;
function getDeliveryMenExecuter()
{
  global $delivery_men_executer;
  if ($delivery_men_executer == null) {
    $delivery_men_executer = new DeliveryMenExecuter();
  }
  return $delivery_men_executer;
}