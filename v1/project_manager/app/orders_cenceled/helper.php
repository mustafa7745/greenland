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