<?php
namespace SU1;

require_once ('helper.php');
class NotificationsExecuter
{
  function executeAddData($title, $description)
  {
    $sendMessage = sendMessageToTobic(2, $title, $description);
    if ($sendMessage) {
      $id = uniqid(rand(), false);
      return getNotificationsHelper()->addData($id, $title, $description);
    }
    exitFromScript("CONT SEND FCM", "CANT");
  }
  function executeGetData()
  {
    return getNotificationsHelper()->getData();
  }


}
$notifications_executer = null;
function getNotificationsExecuter()
{
  global $notifications_executer;
  if ($notifications_executer == null) {
    $notifications_executer = (new NotificationsExecuter());
  }
  return $notifications_executer;
}

