<?php
namespace UserApp;

// require_once (getPath() . 'tables/categories/attribute.php');

// // require_once "../../../ids_controller/helper.php";
// require_once (getUserPath() . 'app/ids_controller/helper.php');
// require_once (getSuPath() . 'app/products_images/helper.php');


require_once ('helper.php');
class UsersLocationsExecuter
{
  function executeGetData($userId)
  {
    $data = getUsersLocationsHelper()->getData($userId);
    return $data;
  }
  function executeAddData($userId, $city, $street, $latLong, $nearTo, $contactPhone)
  {
    $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_Cate";
    $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_Cate";
    exitFromScript($ar, $en);
    // 
    $helper = getUsersLocationsHelper();
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    require_once __DIR__ . '/../../../include/ids_controller/helper.php';
    // $category_id = uniqid(rand(), false);
    $id = getId(getIdsControllerHelper()->getData($helper->table_name));
    $dataAfterAdd = $helper->addData($id, $userId, $city, $street, $latLong, $nearTo, $contactPhone);
    // $dataAfterAdd = $helper->getDataById($id);

    // print_r($dataAfterAdd);
    /**
     * ADD INSERTED VALUES TO ProjectINSERtOperations TABLE
     */

    shared_execute_sql("COMMIT");
    return $dataAfterAdd;
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
$users_locations_executer = null;
function getUsersLocationsExecuter()
{
  global $users_locations_executer;
  if ($users_locations_executer == null) {
    $users_locations_executer = (new UsersLocationsExecuter());
  }
  return $users_locations_executer;
}
