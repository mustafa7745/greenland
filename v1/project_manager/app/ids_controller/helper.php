<?php
namespace Manager;

require_once ('sql.php');
// 
class IdsControllerHelper extends IdsControllerSql
{
  function getData($tableName)
  {
    $sql = $this->readSql("'$tableName'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = $this->name . "_IDName_ERROR";
      $en = $this->name . "_IDName_ERROR";
      exitFromScript($ar, $en);
    }
    $this->updateId($tableName);
    return $data[0];
  }
  function updateId($tableName)
  {
    $sql = $this->updateIdSql("'$tableName'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_Cate";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_Cate";
      exitFromScript($ar, $en);
    }
  }


}

$ids_controller_helper = null;
function getIdsControllerHelper()
{
  global $ids_controller_helper;
  if ($ids_controller_helper == null) {
    $ids_controller_helper = new IdsControllerHelper();
  }
  return $ids_controller_helper;
}