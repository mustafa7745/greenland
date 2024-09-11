<?php
namespace DeliveryMen;

require_once ('helper.php');
class UsersLocationsExecuter
{
  function executeGetData($userLocationId)
  {
    $data = getUsersLocationsHelper()->getData($userLocationId);
    return $data;
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

