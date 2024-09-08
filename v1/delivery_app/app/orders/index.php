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
    function read()
    {
        $loginToken = $this->_check();
        $deliveryManId = $loginToken->deliveryManId;
        return getOrdersExecuter()->executeGetData($deliveryManId);
    }
    function orderOnRoad()
    {
        $loginToken = $this->_check();
        return getOrdersExecuter()->executeOrderInRoad(getInputOrderId());
    }
    function checkCode()
    {
        $loginToken = $this->_check();
        $deliveryManId = $loginToken->deliveryManId;
        return getOrdersExecuter()->executeCheckCode($deliveryManId, getInputOrderId(), getInputOrderCode());
    }
    private function _check()
    {
        $s = getMainRunApp();
        return getDeliveryManLoginToken("RUN_APP", $s);
    }

}
