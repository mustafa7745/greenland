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
    return getDeliveryPrice($data);

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
    $dataAfterAdd = $helper->addData($userId, $city, $street, $latLong, $nearTo, $contactPhone);
    shared_execute_sql("COMMIT");
    $dataAfterAdd["deliveryPrice"] = getDeliveryPrice($dataAfterAdd);
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

