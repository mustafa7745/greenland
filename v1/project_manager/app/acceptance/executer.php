<?php
namespace Manager;

require_once 'helper.php';
class AcceptanceExecuter
{
  function executeGetData($orderDeliveryId)
  {
    $acceptance = getAcceptanceHelper()->getData($orderDeliveryId);
    if ($acceptance != null) {
      require_once (getManagerPath() . "app/delivery_men/helper.php");
      $deliveryMan = getDeliveryMenHelper()->getDataById2($acceptance[getAcceptanceHelper()->deliveryManId]);
      $user = getUsersHelper()->getDataById($userId);
      $acceptance["deliveryMan"] = $deliveryMan;
    }
    return $acceptance;
  }
  function executeAddData($deliveryManId, $orderDeliveryId)
  {
    require_once (getPath() . '/ids_controller/helper.php');
    // getInputDeliver
    $helper = getAcceptanceHelper();
    /**
     *  START TRANSACTION FOR SQL
     */
    require_once __DIR__ . '/../../app/orders/helper.php';
    $orderDelivery = getOrdersDeliveryHelper()->getDataById($orderDeliveryId);
    $orderId = $orderDelivery[getOrdersDeliveryHelper()->orderId];
    $order = getOrdersHelper()->getDataById($orderId);
    if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      $ar = "هذا الطلب تم انجازه";
      $en = "هذا الطلب تم انجازه";
      exitFromScript($ar, $en);
    }
    // 

    shared_execute_sql("START TRANSACTION");
    $acceptance = $helper->getData2($orderDeliveryId);
    if (count($acceptance) == 0) {
      // Level (1)
      $this->processAcceptance($helper, $deliveryManId, $orderDeliveryId, $acceptance);
    } elseif (count($acceptance) == 1) {
      $acceptance = $acceptance[0];
      if ($acceptance[$helper->deliveryManId] == $deliveryManId) {
        $ar = "الموصل لديه الطلب بالفعل";
        $en = "الموصل لديه الطلب بالفعل";
        exitFromScript($ar, $en);
      }
      if ($helper->WAIT_TO_ACCEPT_STATUS == $acceptance[$helper->status]) {
        // Level (2)
        $this->processAcceptance($helper, $deliveryManId, $orderDeliveryId, $acceptance, 2);
      } else {
        // Level (3)
        $this->processAcceptance($helper, $deliveryManId, $orderDeliveryId, $acceptance, 3);
      }

    } else {
      $ar = "طلبات انتظار كثيرة";
      $en = "طلبات انتظار كثيرة";
      exitFromScript($ar, $en);
    }
  }
  private function processAcceptance($helper, $deliveryManId, $orderDeliveryId, $acceptance, $level = 1)
  {
    // 1) Get Reservation Data
    require_once (getManagerPath() . 'app/reservations/helper.php');
    $resrvation = getReservationsHelper()->getData($deliveryManId);
    $resrvation = getReservationsHelper()->getDataById(getId($resrvation));
    // 2) Update Reservation status to Accepted
    getReservationsHelper()->updateStatus(getId($resrvation), getReservationsHelper()->ACCEPTED_RESERVED_STATUS);
    // 3) Add Request Accept To Acceptance Table
    $acceptanceId = getId(getIdsControllerHelper()->getData($helper->table_name));
    $helper->addData($acceptanceId, $deliveryManId, $orderDeliveryId);
    if ($level == 2) {
      $acceptanceId = getId($acceptance);
      $acceptance = $helper->getDataById($acceptanceId);
      //
      $helper->updateStatus($acceptanceId, $helper->NOT_ANSWRED_STATUS);
      require_once (getManagerPath() . 'app/reservations/helper.php');
      getReservationsHelper()->updateAcceptanceId(getId($resrvation), $acceptanceId);
    } elseif ($level == 3) {
      require_once (getManagerPath() . 'app/orders/helper.php');
      $orderDelivery = getOrdersDeliveryHelper()->getDataById($orderDeliveryId);
      getOrdersDeliveryHelper()->updateDeliveryManId($orderDeliveryId);
      // 
      $acceptanceId = getId($acceptance);
      $acceptance = $helper->getDataById($acceptanceId);
      //
      $helper->updateStatus($acceptanceId, $helper->CHANGED_TO_OTHER_STATUS);
      //
      getReservationsHelper()->updateAcceptanceId(getId($resrvation), $acceptanceId);

    }
    shared_execute_sql("COMMIT");


    require_once __DIR__ . '/../../app/delivery_men/helper.php';
    $deliveryMan = getDeliveryMenHelper()->getDataById($deliveryManId);
    $userId = $deliveryMan[getDeliveryMenHelper()->userId];
    require_once __DIR__ . '/../../app/users/helper.php';
    $user = getUsersHelper()->getDataById($userId);
    require_once __DIR__ . '/../../../include/users_sessions_devices_sessions/helper.php';
    $token = getUsersSessionsHelper()->getToken($userId, 3);
    if ($token != null) {
      require_once __DIR__ . '/../../../include/projects/helper.php';
      $project = getProjectsHelper()->getDataById(1);
      require_once __DIR__ . '/../../../include/send_message.php';
      $title = "مرحبا بك: " . $user[getUsersHelper()->name];
      sendMessageToOne($project[getProjectsHelper()->serviceAccountKey], $token, $title, "يرجى قبول الطلب شكرا لك");
    }

    return ["success" => "true"];
  }
}
$acceptance_executer = null;
function getAcceptanceExecuter()
{
  global $acceptance_executer;
  if ($acceptance_executer == null) {
    $acceptance_executer = new AcceptanceExecuter();
  }
  return $acceptance_executer;
}

