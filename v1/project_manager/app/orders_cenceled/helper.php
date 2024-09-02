<?php
namespace Manager;

require_once 'sql.php';
// 
class OrdersCenceledHelper extends OrdersCenceledSql
{
  function addData($orderId, $description)
  {
    $sql = $this->addSql("'$orderId'", "'$description'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED";
      $en = "DATA_NOT_EFFECTED";
      exitFromScript($ar, $en);
    }
  }
  function getDataByOrderId($orderId)
  {
    $sql = $this->readByOrderIdSql("'$orderId'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "CENc_ID_ERROR";
      $en = "CENc_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
}

$orders_cenceled_helper = null;
function getOrdersCenceledHelper()
{
  global $orders_cenceled_helper;
  if ($orders_cenceled_helper == null) {
    $orders_cenceled_helper = new OrdersCenceledHelper();
  }
  return $orders_cenceled_helper;
}