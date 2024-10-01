<?php
namespace UserApp;

require_once 'sql.php';
// 
class UsersHelper extends UsersSql
{
  function getDataById($userId)
  {
    $sql = $this->readByIdSql("'$userId'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "USER_ID";
      $en = "USER_ID";
      exitFromScript($ar, $en);
    }

    return $data[0];
  }
  function getDataById2($userId)
  {
    $sql = $this->readById2Sql("'$userId'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "USER_ID";
      $en = "USER_ID";
      exitFromScript($ar, $en);
    }

    return $data[0];
  }
  function updateName2($id, $newValue)
  {
    $sql = $this->updateName2Sql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      exitFromScript($ar, $en);
    }
    return $this->getDataById2($id);
  }
}

$users_helper = null;
function getUsersHelper()
{
  global $users_helper;
  if ($users_helper == null) {
    $users_helper = new UsersHelper();
  }
  return $users_helper;
}