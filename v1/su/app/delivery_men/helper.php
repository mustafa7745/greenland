<?php
namespace SU1;

require_once ('sql.php');
// 
class DeliveryMenHelper extends DeliveryMenSql
{
  function getData($userId)
  {
    $sql = $this->searchSql("'$userId'");
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
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
  function addData($id, $userId)
  {
    $sql = $this->addSql("'$id'", "'$userId'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateStatus($id)
  {
    $sql = $this->updateStatus("'$id'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
}

$delivery_men_helper = null;
function getDeliveryMenHelper()
{
  global $delivery_men_helper;
  if ($delivery_men_helper == null) {
    $delivery_men_helper = new DeliveryMenHelper();
  }
  return $delivery_men_helper;
}