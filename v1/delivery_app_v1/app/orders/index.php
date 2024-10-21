<?php
namespace DeliveryMen;

// To Get RunApp
require_once "../../../include/check/index_v1.php";
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
        $loginTokenDuration = getRemainedMinute();
        print_r(date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes")));

        return getOrdersExecuter()->executeGetData($deliveryManId);
    }
    function readOrderContent()
    {
        $loginToken = $this->_check();
        $deliveryManId = $loginToken->deliveryManId;
        return getOrdersExecuter()->executeGetOrderContent(getInputOrderId());
    }
    function orderOnRoad()
    {
        $loginToken = $this->_check();
        return getOrdersExecuter()->executeOrderInRoad(getInputOrderId(), $loginToken->deliveryManId);
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
