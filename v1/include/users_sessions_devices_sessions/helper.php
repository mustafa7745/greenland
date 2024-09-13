<?php
require_once 'sql.php';
class UsersSessionsHelper extends UsersSessionsSql
{
  function getToken($userId, $appId)
  {
    $sql = $this->readTokenSql("'$userId'", "'$appId'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) > 0) {
     return $data[0][$this->devices_sessions_attribute->appToken];
    }
    return null;
  }
}

$users_sessions1_helper = null;
function getUsersSessionsHelper()
{
  global $users_sessions1_helper;
  if ($users_sessions1_helper == null) {
    $users_sessions1_helper = new UsersSessionsHelper();
  }
  return $users_sessions1_helper;
}