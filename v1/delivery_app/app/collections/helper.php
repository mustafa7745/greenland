<?php
namespace DeliveryMen;

require_once 'sql.php';
// 
class CollectionsHelper extends CollectionsSql
{

  function addData($userId, $deliveryManId, $sum)
  {
    $sql = $this->addSql("'$userId'", "'$deliveryManId'", "'$sum'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD";
      exitFromScript($ar, $en);
    }
  }
}

$collections_helper = null;
function getCollectionsHelper()
{
  global $collections_helper;
  if ($collections_helper == null) {
    $collections_helper = new CollectionsHelper();
  }
  return $collections_helper;
}