<?php
namespace Manager;

require_once ('sql.php');
// 
class ReservationsHelper extends ReservationsSql
{
  // public $name = "APP";
  function getData($deliveryManId)
  {
    $sql = $this->readsql("'$deliveryManId'");
    // $ar = $sql;
    // $en = "هذا المستخدم لم يحجز بعد";
    // exitFromScript($ar, $en);
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "هذا المستخدم لم يحجز بعد";
      $en = "هذا المستخدم لم يحجز بعد";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getDataById($id)
  {
    $sql = $this->readByIdSql($id);

    $data = shared_execute_read1_no_json_sql($sql);

    if (count($data) != 1) {
      $ar = $this->name . "_ID_ERROR";
      $en = $this->name . "_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function addData($deliveryManId)
  {
    $sql = $this->addSql($deliveryManId);
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_Cate";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_Cate" . $sql;
      exitFromScript($ar, $en);
    }
    // return $this->getDataById($id);
  }

  function searchData($search)
  {
    $sql = $this->search_sql($search);
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }


  // function getDataById($id)
  // {
  //   $sql = $this->read_by_id_sql("'$id'");
  //   $data = shared_execute_read_no_json_sql($sql)->data;
  //   if (count($data) != 1) {
  //     $ar = $this->name . "_ID_ERROR";
  //     $en = $this->name . "_ID_ERROR";
  //     exitFromScript($ar, $en);
  //   }
  //   return $data[0];
  // }

  function updateStatus($id, $newValue)
  {

    $sql = $this->updateStatusSql("'$id'", "'$newValue'");

    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      exitFromScript($ar, $en);
    }
  }
  function updateAcceptanceId($id, $newValue)
  {

    $sql = $this->updateAcceptanceSql("'$id'", "'$newValue'");

    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      exitFromScript($ar, $en);
    }
  }
  function updateSha($id, $name)
  {

    $sql = $this->update_sha_sql("'$name'", "'$id'");

    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_SHA";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_SHA";
      exitFromScript($ar, $en);
    }
  }
  function updateVersion($id, $name)
  {

    $sql = $this->update_version_sql("'$name'", "'$id'");

    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_SHA";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_SHA";
      exitFromScript($ar, $en);
    }
  }

}

$reservations_helper = null;
function getReservationsHelper()
{
  global $reservations_helper;
  if ($reservations_helper == null) {
    $reservations_helper = (new ReservationsHelper());
  }
  return $reservations_helper;
}