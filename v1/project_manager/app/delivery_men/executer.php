<?php
namespace Manager;

require_once 'helper.php';
class DeliveryMenExecuter
{
  function sendMessageToDeliveryMan($deliveryManId, $body)
  {
    require_once __DIR__ . "/../delivery_men/helper.php";
    $deliveryMan = getDeliveryMenHelper()->getDataById($deliveryManId);
    $userId = $deliveryMan[getDeliveryMenHelper()->userId];
    global $DELIVERY_ANDROID_APP;
    // sendMessage($userId, "تم اضافة طلب يرجى متابعته", $DELIVERY_ANDROID_APP);


    require_once __DIR__ . '/../../app/users/helper.php';
    $user = getUsersHelper()->getDataById($userId);
    require_once __DIR__ . '/../../../include/users_sessions_devices_sessions/helper.php';
    $token = getUsersSessionsHelper()->getToken($userId, $DELIVERY_ANDROID_APP);
    if ($token != null) {
      require_once __DIR__ . '/../../../include/projects/helper.php';
      $project = getProjectsHelper()->getDataById(1);
      require_once __DIR__ . '/../../../include/send_message.php';
      $title = "مرحبا بك: " . $user[getUsersHelper()->name];
      sendMessageToOne($project[getProjectsHelper()->serviceAccountKey], $token, $title, $body);
    }
    // $deliveryMan = getDeliveryMenHelper()->getDataById($deliveryManId);
    // $userId = $deliveryMan[getDeliveryMenHelper()->userId];
    // require_once __DIR__ . '/../../app/users/helper.php';
    // $user = getUsersHelper()->getDataById($userId);
    // require_once __DIR__ . '/../../../include/users_sessions_devices_sessions/helper.php';
    // $token = getUsersSessionsHelper()->getToken($userId, 3);
    // if ($token != null) {
    //   require_once __DIR__ . '/../../../include/projects/helper.php';
    //   $project = getProjectsHelper()->getDataById(1);
    //   require_once __DIR__ . '/../../../include/send_message.php';
    //   $title = "مرحبا بك: " . $user[getUsersHelper()->name];
    //   sendMessageToOne($project[getProjectsHelper()->serviceAccountKey], $token, $title, "يرجى قبول الطلب شكرا لك");
    // }
  }

}
$delivery_men_executer = null;
function getDeliveryMenExecuter()
{
  global $delivery_men_executer;
  if ($delivery_men_executer == null) {
    $delivery_men_executer = new DeliveryMenExecuter();
  }
  return $delivery_men_executer;
}

