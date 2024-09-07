<?php
namespace Manager;

require_once ('sql.php');
// 
class ManagersUsersHelper extends ManagersUsersSql
{
  function getDataById($id)
  {
    $sql = $this->readByIdSql("'$id'");

    $data = shared_execute_read1_no_json_sql($sql);

    if (count($data) != 1) {
      $ar = $this->name . "_ID_ERROR";
      $en = $this->name . "_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function addData($id, $userId, $managerId)
  {
    $sql = $this->addSql("'$id'", "'$userId'", "'$managerId'");
    // print_r($sql);
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
 
}

$users_helper = null;
function getManagersUsersHelper()
{
  global $users_helper;
  if ($users_helper == null) {
    $users_helper = (new ManagersUsersHelper());
  }
  return $users_helper;
}