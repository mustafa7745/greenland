<?php
namespace Manager;

require_once ('helper.php');
class ReservationsExecuter
{
  function executeGetData($phone)
  {
    require_once (getManagerPath() . 'app/users/helper.php');
    $user = getUsersHelper()->getData($phone);
    require_once (getManagerPath() . 'app/delivery_men/helper.php');
    $deliveryMan = getDeliveryMenHelper()->getDataById(getId($user));
    $reservation = getReservationsHelper()->getData(getId($deliveryMan));
    return $user;
  }
  function executeAddData($deliveryManId)
  {
    $helper = getReservationsHelper();
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    if (count($helper->getData($deliveryManId)) > 0) {
      $ar = "تم الحجز مسبقا";
      $en = "Reserved";
      exitFromScript($ar, $en);
    }
    ;

    // $category_id = uniqid(rand(), false);
    // $id = getId(getIdsControllerHelper()->getData($helper->table_name));
    $dataAfterAdd = $helper->addData($deliveryManId);
    // $dataAfterAdd = $helper->getDataById($id);

    // print_r($dataAfterAdd);
    /**
     * ADD INSERTED VALUES TO ProjectINSERtOperations TABLE
     */

    shared_execute_sql("COMMIT");
    return ["success" => "true"];
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
$reservations_executer = null;
function getReservationsExecuter()
{
  global $reservations_executer;
  if ($reservations_executer == null) {
    $reservations_executer = (new ReservationsExecuter());
  }
  return $reservations_executer;
}

