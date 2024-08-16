<?php
namespace DeliveryMen;

require_once (getPath() . 'tables/categories/attribute.php');

// require_once "../../../ids_controller/helper.php";
require_once (getUserPath() . 'app/ids_controller/helper.php');
require_once (getSuPath() . 'app/products_images/helper.php');


require_once ('helper.php');
class AcceptanceExecuter
{
  function executeGetData($deliveryManId, $status)
  {
    $data = getAcceptanceHelper()->getData2($deliveryManId, $status);
    return $data;
  }
  function executeAddData($deliveryManId, $orderDeliveryId)
  {
    // getInputDeliver
    $helper = getAcceptanceHelper();
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    require_once (getManagerPath() . 'app/reservations/helper.php');
    $resrvation = getReservationsHelper()->getData($deliveryManId);
    $resrvation = getReservationsHelper()->getDataById(getId($resrvation));
    // 
    getReservationsHelper()->updateStatus(getId($resrvation), getReservationsHelper()->ACCEPTED_RESERVED_STATUS);


    // $category_id = uniqid(rand(), false);
    // $id = getId(getIdsControllerHelper()->getData($helper->table_name));
    $helper->addData($deliveryManId, $orderDeliveryId);
    // $dataAfterAdd = $helper->getDataById($id);

    // print_r($dataAfterAdd);
    /**
     * ADD INSERTED VALUES TO ProjectINSERtOperations TABLE
     */

    shared_execute_sql("COMMIT");
    return ["success" => "true"];
    ;
  }
  function executeSearchData($search)
  {
    return getAppsHelper()->searchData($search);
  }
  function executeUpdateName($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    $apps_helper = getAppsHelper();
    $app = $apps_helper->getDataById($id);
    $updated_id = getId($app);
    $preValue = getPackageName($app);
    $apps_helper->updateName($id, $newValue);
    $dataAfterUpdate = $apps_helper->getDataById($id);
    /**
     * ADD Updated VALUE TO UserUpdatedOperations TABLE
     */
    // sharedAddUserUpdateOperation($data->getUserId(), $data->getPermissionId(), $data->getUserSessionId(), $updated_id, $preValue, $newValue);

    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateStatusAccept($deliveryManId)
  {

    // sleep(5);
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    $acceptance = $this->executeGetData($deliveryManId, getAcceptanceHelper()->WAIT_TO_ACCEPT_STATUS);
    // print_r($acceptance);
    if (count($acceptance) != 1) {
      $ar = "لايمكن قبول الطلب نظرا لتغييره مسبقا";
      $en = "لايمكن قبول الطلب نظرا لتغييره مسبقا";
      exitFromScript($ar, $en);
    }
    $acceptance = $acceptance[0];


    require_once __DIR__ . '/../../app/orders/helper.php';
    $orderDelivery = getOrdersDeliveryHelper()->getDataById(getOrderDeliveryId($acceptance));
    getOrdersDeliveryHelper()->updateDeliveryManId(getId($orderDelivery), $deliveryManId);
    $order = getOrdersHelper()->getDataById($orderDelivery[getOrdersDeliveryHelper()->orderId]);
    // 
    // print_r($order);
    // exitFromScript("",json_encode($order));
    $situatinId = getOrdersHelper()->ORDER_ASSIGNED_DELIVERY_MAN;
    getOrdersHelper()->updateStatus(getId($order), $situatinId);
    getOrdersStatusHelper()->addData(getId($order), $situatinId);



    getAcceptanceHelper()->updateStatus(getId($acceptance), getAcceptanceHelper()->ACCEPTED_STATUS);

    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return ["success" => ""];
  }
  function executeUpdateVersion($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    $apps_helper = getAppsHelper();
    $app = $apps_helper->getDataById($id);
    $updated_id = getId($app);
    $preValue = getPackageName($app);
    $apps_helper->updateVersion($id, $newValue);
    $dataAfterUpdate = $apps_helper->getDataById($id);
    /**
     * ADD Updated VALUE TO UserUpdatedOperations TABLE
     */
    // sharedAddUserUpdateOperation($data->getUserId(), $data->getPermissionId(), $data->getUserSessionId(), $updated_id, $preValue, $newValue);

    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
}
$acceptance_executer = null;
function getAcceptanceExecuter()
{
  global $acceptance_executer;
  if ($acceptance_executer == null) {
    $acceptance_executer = (new AcceptanceExecuter());
  }
  return $acceptance_executer;
}

