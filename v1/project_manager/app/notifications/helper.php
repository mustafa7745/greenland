<?php
namespace Manager;

require_once 'sql.php';
// 
class NotificationsHelper extends NotificationsSql
{
  function getDataById($id)
  {
    $sql = $this->readByIdSql("'$id'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "ID_ERROR";
      $en = "D_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getData()
  {
    $sql = $this->readsql();
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function addData($id, $title, $description)
  {
    $sql = $this->addSql("'$id'", "'$title'", "'$description'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_N";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_N";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
}

$notifications_helper = null;
function getNotificationsHelper()
{
  global $notifications_helper;
  if ($notifications_helper == null) {
    $notifications_helper = (new NotificationsHelper());
  }
  return $notifications_helper;
}