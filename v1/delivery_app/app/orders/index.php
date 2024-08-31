<?php
namespace DeliveryMen;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';
class Orders
{
    function orderOnRoad()
    {
        $s = getMainRunApp();
        $modelDeliveryManLoginTokenUserSession = getDeliveryManLoginToken("RUN_APP", $s);
        $modelDeliveryManLoginTokenUserSession->deliveryManId;
        return getOrdersExecuter()->executeOrderInRoad(getInputOrderId());
    }
    function checkCode()
    {
        $s = getMainRunApp();
        $modelDeliveryManLoginTokenUserSession = getDeliveryManLoginToken("RUN_APP", $s);
        $deliveryManId = $modelDeliveryManLoginTokenUserSession->deliveryManId;
        return getOrdersExecuter()->executeCheckCode($deliveryManId, getInputOrderId(), getInputOrderCode());
    }

}
