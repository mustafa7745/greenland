<?php
namespace SU1;

require_once ('helper.php');
class UsersExecuter
{
  function executeGetData($phone)
  {
    return getUsersHelper()->getData($phone);
  }
  function executeUpdateStatus($id)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    $dataAfterUpdate = getUsersHelper()->updateStatus($id);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
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

