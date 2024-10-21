<?php
namespace DeliveryMen;

require_once 'sql.php';
// 
class UsersLocationsHelper extends UsersLocationsSql
{
  function getData($userLocationId)
  {
    $sql = $this->readByIdsql("'$userLocationId'");
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }

}

$users_locations_helper = null;
function getUsersLocationsHelper()
{
  global $users_locations_helper;
  if ($users_locations_helper == null) {
    $users_locations_helper = (new UsersLocationsHelper());
  }
  return $users_locations_helper;
}