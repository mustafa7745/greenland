<?php
namespace Manager;

require_once ('sql.php');
// 
class AcceptanceHelper extends AcceptanceSql
{
  // public $name = "APP";
  function getData($orderDeliveryId)
  {
    $sql = $this->readSql("'$orderDeliveryId'");

    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      return null;
    }
    return $data[0];
  }
  function getData2($orderDeliveryId)
  {
    $sql = $this->readSql("'$orderDeliveryId'");

    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getDataByOrderDeliveryIdAndStatus($orderDeliveryId, $status)
  {
    $sql = $this->readByOrderDeliveryIdAndStatusSql("'$orderDeliveryId'", "'$status'");
    // print_r($sql);
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
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
  function addData($id, $deliveryManId, $orderDeliveryId)
  {
    $sql = $this->addSql("'$id'", "'$deliveryManId'", "'$orderDeliveryId'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_Cate";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_Cate" . $sql;
      exitFromScript($ar, $en);
    }
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
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
  }
  function deleteData($ids, $count)
  {
    $sql = $this->deleteSql($ids);
    print_r($sql);
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != $count) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_DELETE_";
      $en = "DATA_NOT_EFFECTED_WHEN_DELETE_";
      exitFromScript($ar, $en);
    }
  }
}

$uacceptance_helper = null;
function getAcceptanceHelper()
{
  global $uacceptance_helper;
  if ($uacceptance_helper == null) {
    $uacceptance_helper = (new AcceptanceHelper());
  }
  return $uacceptance_helper;
}