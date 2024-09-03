<?php
namespace Manager;

require_once ('helper.php');
class ReservationsExecuter
{
  function executeGetData($phone)
  {
    require_once (getManagerPath() . 'app/users/helper.php');
    $user = getUsersHelper()->getData($phone);
    require_once (getManagerPath() . 'app/delivery_men/helper.php');
    $deliveryMan = getDeliveryMenHelper()->getDataById(getId($user));
    $reservation = getReservationsHelper()->getData(getId($deliveryMan));
    return $user;
  }
}
$reservations_executer = null;
function getReservationsExecuter()
{
  global $reservations_executer;
  if ($reservations_executer == null) {
    $reservations_executer = (new ReservationsExecuter());
  }
  return $reservations_executer;
}

