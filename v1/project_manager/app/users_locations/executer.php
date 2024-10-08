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
    for ($i = 0; $i < count($data); $i++) {
      $data[$i]['price'] = getDeliveryPrice($data[$i]);;
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

    $dataAfterAdd = $helper->addData($userId, $city, $street, $latLong, $nearTo, $contactPhone);
   

    shared_execute_sql("COMMIT");

    $dataAfterAdd['price'] = getDeliveryPrice($dataAfterAdd);
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

