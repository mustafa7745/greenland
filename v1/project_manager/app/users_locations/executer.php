<?php
namespace Manager;

require_once ('helper.php');
class UsersLocationsExecuter
{
  function executeGetData($userId)
  {
    global $PROJECT_ID;
    require_once __DIR__ . '/../../../include/projects/helper.php';
    $data = getUsersLocationsHelper()->getData($userId);
    $project = getProjectsHelper()->getDataById($PROJECT_ID);
    $project_lat = (getLatLong($project))[0];
    $project_long = (getLatLong($project))[1];
    for ($i = 0; $i < count($data); $i++) {
      $user_lat = (getLatLong($data[$i]))[0];
      $user_long = (getLatLong($data[$i]))[1];
      $distanse = haversine_distance($project_lat, $project_long, $user_lat, $user_long);
      $data[$i]['price'] = 50 * round(($distanse * getPriceDeliveryPer1Km($project)) / 50);
    }

    return $data;
  }
  function executeGetDataById($id)
  {
    return getUsersLocationsHelper()->getDataById($id);
  }
  function executeAddData($userId, $city, $street, $latLong, $nearTo, $contactPhone)
  {
    $helper = getUsersLocationsHelper();
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    // $category_id = uniqid(rand(), false);
    // require_once __DIR__ . '/../ids_controller/helper.php';
    // $id = getId(getIdsControllerHelper()->getData($helper->table_name));
    $dataAfterAdd = $helper->addData($userId, $city, $street, $latLong, $nearTo, $contactPhone);
    // $dataAfterAdd = $helper->getDataById($id);

    // print_r($dataAfterAdd);
    /**
     * ADD INSERTED VALUES TO ProjectINSERtOperations TABLE
     */

    shared_execute_sql("COMMIT");
    // require_once __DIR__ . '/../../../include/projects/helper.php';
    // global $PROJECT_ID;
    // $project = getProjectsHelper()->getDataById($PROJECT_ID);
    // $project_lat = (getLatLong($project))[0];
    // $project_long = (getLatLong($project))[1];
    // // 
    // $user_lat = (getLatLong($dataAfterAdd))[0];
    // $user_long = (getLatLong($dataAfterAdd))[1];
    // $distanse = haversine_distance($project_lat, $project_long, $user_lat, $user_long);

    $dataAfterAdd['price'] = getDeliveryPrice($dataAfterAdd);
    //  50 * round(($distanse * getPriceDeliveryPer1Km($project)) / 50);
    return $dataAfterAdd;
  }
  function executeUpdateStreet($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $dataAfterUpdate = getUsersLocationsHelper()->updateStreet($id, $newValue);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateUrl($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $dataAfterUpdate = getUsersLocationsHelper()->updateUrl($id, $newValue);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateLatLong($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $dataAfterUpdate = getUsersLocationsHelper()->updateLatLong($id, $newValue);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateContactPhone($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $dataAfterUpdate = getUsersLocationsHelper()->updateContactPhone($id, $newValue);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateNearTo($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $dataAfterUpdate = getUsersLocationsHelper()->updateNearTo($id, $newValue);
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

