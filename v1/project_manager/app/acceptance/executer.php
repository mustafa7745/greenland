<?php
namespace Manager;

require_once (getPath() . 'tables/categories/attribute.php');

// require_once "../../../ids_controller/helper.php";

require_once (getSuPath() . 'app/products_images/helper.php');


require_once ('helper.php');
class AcceptanceExecuter
{
  function executeGetData($userId)
  {
    $data = getUsersLocationsHelper()->getData($userId);
    return $data;
  }
  function executeAddData($deliveryManId, $orderDeliveryId)
  {
    require_once (getPath() . '/ids_controller/helper.php');
    // getInputDeliver
    $helper = getAcceptanceHelper();
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $acceptance = $helper->getData2($orderDeliveryId);
    if (count($acceptance) == 0) {
      // Level (1)
      $this->processAcceptance($helper, $deliveryManId, $orderDeliveryId, $acceptance);
    } elseif (count($acceptance) == 1) {
      $acceptance = $acceptance[0];
      if ($acceptance[$helper->deliveryManId] == $deliveryManId) {
        $ar = "الموصل لديه الطلب بالفعل";
        $en = "الموصل لديه الطلب بالفعل";
        exitFromScript($ar, $en);
      }
      if ($helper->WAIT_TO_ACCEPT_STATUS == $acceptance[$helper->status]) {
        // Level (2)
        $this->processAcceptance($helper, $deliveryManId, $orderDeliveryId, $acceptance, 2);
      } else {
        // Level (3)
        $this->processAcceptance($helper, $deliveryManId, $orderDeliveryId, $acceptance, 3);
      }

    } else {
      $ar = "طلبات انتظار كثيرة";
      $en = "طلبات انتظار كثيرة";
      exitFromScript($ar, $en);
    }





    ;
  }
  private function processAcceptance($helper, $deliveryManId, $orderDeliveryId, $acceptance, $level = 1)
  {
    // 1) Get Reservation Data
    require_once (getManagerPath() . 'app/reservations/helper.php');
    $resrvation = getReservationsHelper()->getData($deliveryManId);
    $resrvation = getReservationsHelper()->getDataById(getId($resrvation));
    // 2) Update Reservation status to Accepted
    getReservationsHelper()->updateStatus(getId($resrvation), getReservationsHelper()->ACCEPTED_RESERVED_STATUS);
    // 3) Add Request Accept To Acceptance Table
    $acceptanceId = getId(getIdsControllerHelper()->getData($helper->table_name));
    $helper->addData($acceptanceId, $deliveryManId, $orderDeliveryId);
    if ($level == 2) {
      $acceptanceId = getId($acceptance);
      $acceptance = $helper->getDataById($acceptanceId);
      //
      $helper->updateStatus($acceptanceId, $helper->NOT_ANSWRED_STATUS);
      require_once (getManagerPath() . 'app/reservations/helper.php');
      getReservationsHelper()->updateAcceptanceId(getId($resrvation), $acceptanceId);
    } elseif ($level == 3) {
      require_once (getManagerPath() . 'app/orders/helper.php');
      $orderDelivery = getOrdersDeliveryHelper()->getDataById($orderDeliveryId);
      getOrdersDeliveryHelper()->updateDeliveryManId($orderDeliveryId);
      // 
      $acceptanceId = getId($acceptance);
      $acceptance = $helper->getDataById($acceptanceId);
      //
      $helper->updateStatus($acceptanceId, $helper->CHANGED_TO_OTHER_STATUS);
      //
      getReservationsHelper()->updateAcceptanceId(getId($resrvation), $acceptanceId);

    }
    require_once __DIR__ . '/../../../include/projects/helper.php';
    $project = getProjectsHelper()->getDataById(1);
    require_once __DIR__ . '/../../app/delivery_men/helper.php';
    $deliveryMan = getDeliveryMenHelper()->getDataById($deliveryManId);
    $userId = $deliveryMan[getDeliveryMenHelper()->userId];
    require_once __DIR__ . '/../../../include/users_sessions/helper.php';
    $token = getUsersSessionsHelper()->getToken($userId, 2);
    if ($token != null) {
      sendMessageToOne($project[getProjectsHelper()->serviceAccountKey], $token, "مرحبا بك", "يرجى قبول الطلب شكرا لك");
    }
    shared_execute_sql("COMMIT");

    return ["success" => "true"];
  }
  // function executeAddDataAndRejectPrevious($acceptanceId, $deliveryManId, $orderDeliveryId)
  // {
  //   // getInputDeliver
  //   $helper = getAcceptanceHelper();
  //   /**
  //    *  START TRANSACTION FOR SQL
  //    */
  //   shared_execute_sql("START TRANSACTION");
  //   // $helper->updateName()

  //   require_once (getManagerPath() . 'app/reservations/helper.php');
  //   $resrvation = getReservationsHelper()->getData($deliveryManId);
  //   $resrvation = getReservationsHelper()->getDataById(getId($resrvation));
  //   // 
  //   getReservationsHelper()->updateStatus(getId($resrvation), getReservationsHelper()->ACCEPTED_RESERVED_STATUS);


  //   // $category_id = uniqid(rand(), false);
  //   // $id = getId(getIdsControllerHelper()->getData($helper->table_name));
  //   $helper->addData($deliveryManId, $orderDeliveryId);
  //   // $dataAfterAdd = $helper->getDataById($id);

  //   // print_r($dataAfterAdd);
  //   /**
  //    * ADD INSERTED VALUES TO ProjectINSERtOperations TABLE
  //    */

  //   shared_execute_sql("COMMIT");
  //   return ["success" => "true"];
  //   ;
  // }
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
  function executeUpdateSha($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    $apps_helper = getAppsHelper();
    $app = $apps_helper->getDataById($id);
    $updated_id = getId($app);
    $preValue = getPackageName($app);
    $apps_helper->updateSha($id, $newValue);
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

