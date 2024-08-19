<?php
namespace Manager;

require_once ('helper.php');
class UsersExecuter
{
  function executeGetData($phone)
  {
    return getUsersHelper()->getData($phone);
  }


}
$users_executer = null;
function getUsersExecuter()
{
  global $users_executer;
  if ($users_executer == null) {
    $users_executer = (new UsersExecuter());
  }
  return $users_executer;
}

