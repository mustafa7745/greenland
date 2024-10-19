<?php
namespace UserApp;

require_once 'helper.php';
class UsersExecuter
{
  function executeUpdateName($id, $newValue)
  {
    return getUsersHelper()->updateName2($id, $newValue);
  }
}

$users_executer = null;
function getUsersExecuter()
{
  global $users_executer;
  if ($users_executer == null) {
    $users_executer = new UsersExecuter();
  }
  return $users_executer;
}