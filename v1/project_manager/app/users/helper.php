<?php
namespace Manager;

require_once ('sql.php');
// 
class UsersHelper extends UsersSql
{
  function getData($phone)
  {
    $sql = $this->readsql("'$phone'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "لايوجد مستخدم برقم الهاتف هذا";
      $en = $this->name . "_ID_ERROR";
      exitFromScript($ar, $en, 400, 1400);
    }
    return $data[0];
  }
  function getData2($phone)
  {
    $sql = $this->readsql("'$phone'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      return null;
    }
    return $data[0];
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
  function addData($id, $phone, $name, $password)
  {
    shared_execute_sql("START TRANSACTION");

    $sql = $this->addSql("'$id'", "'$phone'", "'$name'", "'$password'");
    print_r($sql);
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD";
      exitFromScript($ar, $en);
    }
    $this->getDataById($id);
  }
  function updatePassword($id, $newValue)
  {
    $sql = $this->updatePasswordSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
    $this->getDataById($id);
  }
  function updateName($id, $newValue)
  {
    $sql = $this->updateNameSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE";
      exitFromScript($ar, $en);
    }
    $this->getDataById($id);

  }
}

$users_helper = null;
function getUsersHelper()
{
  global $users_helper;
  if ($users_helper == null) {
    $users_helper = (new UsersHelper());
  }
  return $users_helper;
}