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
    // Retrieve delivery men data
    $deliveryMen = getDeliveryMenHelper()->getData2();
    $deliveryMenIds = array_column($deliveryMen, 'id');

    // Initialize ordersDelivery for each delivery man
    foreach ($deliveryMen as &$deliveryMan) {
      $deliveryMan['ordersDelivery'] = [];
    }

    // Include required files and get orders
    require_once __DIR__ . "/../orders/helper.php";
    $orders = getOrdersHelper()->getNotComplete();
    $ordersIds = array_column($orders, 'id');

    // Retrieve orders delivery data
    $ordersDelivery = getOrdersDeliveryHelper()->getDataByOrderIdsAndDeliveryManIds(
      convertIdsListToStringSql($ordersIds),
      convertIdsListToStringSql($deliveryMenIds)
    );

    // Convert delivery men array to associative array for faster lookup
    $deliveryMenMap = array_column($deliveryMen, null, 'id');

    // Map ordersDelivery to corresponding delivery men
    foreach ($ordersDelivery as $delivery) {
      $deliveryManId = $delivery['deliveryManId'];
      if (isset($deliveryMenMap[$deliveryManId])) {
        $deliveryMenMap[$deliveryManId]['ordersDelivery'][] = [
          'id' => $delivery['id'],
          'orderId' => $delivery['orderId']
        ];
      }
    }

    // Return the result
    return array_values($deliveryMenMap);
  }

  function executeGetAmountNotcompleteOrders($orderIds)
  {
    $result = [];
    foreach ($orderIds as $orderId) {
      $orderContent = (new \OrderContent());
      $orderContent->executeGetData($orderId);
      $sum = $sum + $orderContent->getActualAmount();
      // 
      $r = ['orderId' => $orderId, 'sum' => $sum];
      array_push($result, $r);
    }
    return $result;
  }


  // function getDeliveryMen()
  // {
  //   $deliveryMen = getDeliveryMenHelper()->getData2();
  //   $deliveryMenIds = [];
  //   for ($i = 0; $i < count($deliveryMen); $i++) {
  //     $id = $deliveryMen[$i]['id'];
  //     array_push($deliveryMenIds, $id);
  //     $deliveryMen[$i]["ordersDelivery"] = [];
  //   }

  //   require_once __DIR__ . "/../orders/helper.php";
  //   $orders = getOrdersHelper()->getNotComplete();
  //   $ordersIds = [];
  //   for ($i = 0; $i < count($orders); $i++) {
  //     $id = $orders[$i]['id'];
  //     array_push($ordersIds, $id);
  //   }
  //   $ordersDelivery = getOrdersDeliveryHelper()->getDataByOrderIdsAndDeliveryManIds(convertIdsListToStringSql($ordersIds), convertIdsListToStringSql($deliveryMenIds));

  //   for ($i = 0; $i < count($ordersDelivery); $i++) {
  //     for ($j = 0; $j < count($deliveryMen); $j++) {
  //       if ($ordersDelivery[$i]["deliveryManId"] == $deliveryMen[$j]['id']) {
  //         $data = ['id' => $ordersDelivery[$i]['id'], 'orderId' => $ordersDelivery[$i]['orderId']];
  //         array_push($deliveryMen[$j]["ordersDelivery"], $data);
  //       }
  //     }
  //   }
  //   return $deliveryMen;
  // }

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

