<?php
namespace DeliveryMen;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class Acceptance
{
    function reject()
    {
        $s = getMainRunApp();
        $modelDeliveryManLoginTokenUserSession = getDeliveryManLoginToken("RUN_APP", $s);
        $deliveryManId = $modelDeliveryManLoginTokenUserSession->deliveryManId;
        return getAcceptanceExecuter()->executeUpdateStatusReject($deliveryManId);
    }
    function accept()
    {
        $s = getMainRunApp();
        $modelDeliveryManLoginTokenUserSession = getDeliveryManLoginToken("RUN_APP", $s);
        $deliveryManId = $modelDeliveryManLoginTokenUserSession->deliveryManId;
        return getAcceptanceExecuter()->executeUpdateStatusAccept($deliveryManId);
    }
    function add()
    {

        $s = getMainRunApp();
        $modelDeliveryManLoginTokenUserSession = getDeliveryManLoginToken("RUN_APP", $s);
        $deliveryManId = $modelDeliveryManLoginTokenUserSession->deliveryManId;
        return getAcceptanceExecuter()->executeAddData($deliveryManId, getInputOrderDeliveryId());
    }
}

