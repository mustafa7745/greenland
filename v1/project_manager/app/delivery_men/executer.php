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
  }

  function getDeliveryMen()
  {
    $deliveryMen = getDeliveryMenHelper()->getData2();
    $deliveryMenIds = [];
    for ($i = 0; $i < count($deliveryMen); $i++) {
      $id = $deliveryMen[$i]['id'];
      array_push($deliveryMenIds, $id);
      $deliveryMen[$i]["ordersDelivery"] = [];
    }
    // 
    require_once __DIR__ . "/../orders/helper.php";
    $orders = getOrdersHelper()->getNotComplete();
    $ordersIds = [];
    for ($i = 0; $i < count($orders); $i++) {
      $id = $orders[$i]['id'];
      array_push($ordersIds, $id);
    }
    // print_r($ordersIds);
    // print_r($deliveryMenIds);

    $ordersDelivery = getOrdersDeliveryHelper()->getDataByOrderIdsAndDeliveryManIds(convertIdsListToStringSql($ordersIds), convertIdsListToStringSql($deliveryMenIds));
    // print_r($ordersDelivery);
    for ($i = 0; $i < count($ordersDelivery); $i++) {
      $lista = [];
      for ($j = 0; $j < count($deliveryMen); $j++) {
        if ($ordersDelivery[$i]["deliveryManId"] == $deliveryMen[$j]['id']) {
          $data = ['id' => $ordersDelivery[$i]['id'], 'orderId' => $ordersDelivery[$i]['orderId']];
          array_push($lista, $ordersDelivery[$i]);
        }
      }
      $deliveryMen[$i]["ordersDelivery"] = $lista;
    }
    return $deliveryMen;
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

