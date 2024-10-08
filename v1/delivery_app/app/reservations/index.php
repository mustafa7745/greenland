<?php
namespace DeliveryMen;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class Reservations
{
    function read()
    {
        $s = getMainRunApp();
        $modelDeliveryManLoginTokenUserSession = getDeliveryManLoginToken("RUN_APP", $s);
        $deliveryManId = $modelDeliveryManLoginTokenUserSession->deliveryManId;
        return getReservationsExecuter()->executeGetData($deliveryManId);
    }
    function add()
    {
        $s = getMainRunApp();
        $modelDeliveryManLoginTokenUserSession = getDeliveryManLoginToken("RUN_APP", $s);
        $deliveryManId = $modelDeliveryManLoginTokenUserSession->deliveryManId;
        return getReservationsExecuter()->executeAddData($deliveryManId);
    }
}

