<?php
namespace Manager;

require_once ('helper.php');
class UsersExecuter
{
  function executeGetData($phone)
  {
    return getUsersHelper()->getData($phone);
  }
  function executeGetDataById($id)
  {
    return getUsersHelper()->getDataById($id);
  }
  function executeAddData($name, $phone, $managerId)
  {
    shared_execute_sql("START TRANSACTION");
    $user = getUsersHelper()->getData2($phone);
    if ($user == null) {
      $id = uniqid(rand(), false);
      $password = generateRandomPassword();
      $user = getUsersHelper()->addData($id, $phone, $name, $password);
      $id = uniqid(rand(), false);
      require_once __DIR__ . '/../managers_users/helper.php';
      getManagersUsersHelper()->addData($id, $user['id'], $managerId);
      shared_execute_sql("COMMIT");
      return $user;
    }
    $ar = "ايوجد مستخدم برقم الهاتف هذا";
    $en = "ايوجد مستخدم برقم الهاتف هذا";
    exitFromScript($ar, $en);
  }

  function executeUpdateName($id, $newValue)
  {
    return getUsersHelper()->updateName($id, $newValue);
  }
  function executeUpdatePassword($id, $newValue)
  {
    return getUsersHelper()->updatePassword($id, $newValue);
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

