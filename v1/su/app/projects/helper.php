<?php
namespace SU1;

require_once ('sql.php');
// 
class ProjectsHelper extends ProjectsSql
{
  function getDataById($id)
  {
    $sql = $this->readByIdSql("'$id'");
    $data = shared_execute_read1_no_json_sql($sql);

    if (count($data) != 1) {
      $ar = "_ID_ERROR";
      $en = "_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function updatePassword($id, $newValue)
  {
    $sql = $this->updatePasswordSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateDeviceId($id, $newValue)
  {
    $sql = $this->updateDeviceIdSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateLatLong($id, $newValue)
  {
    $sql = $this->updateLatLongSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateRequestOrderStatus($id)
  {
    $sql = $this->updateRequestOrderStatusSql("'$id'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateRequestOrderMessage($id, $newValue)
  {
    $sql = $this->updateRequestOrderMessageSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updatePriceDeliveryPer1km($id, $newValue)
  {
    $sql = $this->updatePriceDeliveryPer1kmSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
}

$projects_helper = null;
function getProjectsHelper()
{
  global $projects_helper;
  if ($projects_helper == null) {
    $projects_helper = new ProjectsHelper();
  }
  return $projects_helper;
}