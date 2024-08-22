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
    return $data;
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