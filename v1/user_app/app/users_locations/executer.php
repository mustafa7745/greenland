<?php
namespace UserApp;

require_once 'helper.php';
class UsersLocationsExecuter
{
  function executeGetData($userId)
  {
    $data = getUsersLocationsHelper()->getData($userId);
    return $data;
  }
  function executeGetDeliveryPrice($userLocationId)
  {
    $data = getUsersLocationsHelper()->getDataById($userLocationId);
    $data["deliveryPrice"] = getDeliveryPrice($data);
    return $data;
  }
  function executeAddData($userId, $city, $street, $latLong, $nearTo, $contactPhone)
  {
    $helper = getUsersLocationsHelper();
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $data = getUsersLocationsHelper()->getData($userId);
    if (count($data) > 7) {
      $ar = "لايمكنك اضافة المزيد تواصل مع الادارة";
      $en = "لايمكنك اضافة المزيد تواصل مع الادارة";
      exitFromScript($ar, $en);
    }
    // 
    require_once __DIR__ . '/../../../include/ids_controller/helper.php';
    $id = getId(getIdsControllerHelper()->getData($helper->table_name));
    $dataAfterAdd = $helper->addData($id, $userId, $city, $street, $latLong, $nearTo, $contactPhone);
    shared_execute_sql("COMMIT");
    return $dataAfterAdd;
  }
}
$users_locations_executer = null;
function getUsersLocationsExecuter()
{
  global $users_locations_executer;
  if ($users_locations_executer == null) {
    $users_locations_executer = new UsersLocationsExecuter();
  }
  return $users_locations_executer;
}

