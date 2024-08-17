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