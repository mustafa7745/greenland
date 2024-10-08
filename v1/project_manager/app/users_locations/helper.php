<?php
namespace Manager;

require_once ('sql.php');
// 
class UsersLocationsHelper extends UsersLocationsSql
{
  function getData($userId)
  {
    $sql = $this->readByUserIdsql("'$userId'");
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getDataById($id)
  {
    $sql = $this->readByIdSql("'$id'");

    $data = shared_execute_read1_no_json_sql($sql);

    if (count($data) != 1) {
      $ar = $this->name . "_ID_ERROR";
      $en = $this->name . "_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function addData($userId, $city, $street, $latLong, $nearTo, $contactPhone)
  {
    $sql = $this->addSql("'$userId'", "'$city'", "'$street'", "'$latLong'", "'$nearTo'", "'$contactPhone'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_Cate";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_Cate" . $sql;
      exitFromScript($ar, $en);
    }
    $id = getDB()->conn->insert_id;
    return $this->getDataById($id);
  }
  function updateStreet($id, $newValue)
  {
    $sql = $this->updateStreetSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateUrl($id, $newValue)
  {
    $sql = $this->updateUrlSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateLatLong($id, $newValue)
  {
    $sql = $this->updateLatLongSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateNearTo($id, $newValue)
  {
    $sql = $this->updateNearToSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateContactPhone($id, $newValue)
  {
    $sql = $this->updateContactPhoneSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
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