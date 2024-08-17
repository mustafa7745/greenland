<?php
namespace Manager;

require_once 'sql.php';
// 
class CollectionsHelper extends CollectionsSql
{
  function getData($deliveryManId)
  {
    $sql = $this->readSql("'$deliveryManId'");
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getDataByIds($ids)
  {
    $sql = $this->readByIdsSql($ids);
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function updateIsCollect($ids, $count)
  {
    $sql = $this->updateIsCollectSql($ids);
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != $count) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_DELETE_";
      $en = "DATA_NOT_EFFECTED_WHEN_DELETE_";
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