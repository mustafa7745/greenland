<?php
namespace Manager;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'helper.php';

class DeliveryMen
{
    function search()
    {
        $this->_check("RUN_APP");
        return getDeliveryMenHelper()->getDataByUserPhone(getInputUserPhone3());
    }
    function readById()
    {
        $this->_check("RUN_APP");
        $deliveryMan = getDeliveryMenHelper()->getDataById2(getInputDeliveryManId());
        // 
        return $deliveryMan;
    }
    private function _check($permissionName)
    {
        $s = getMainRunApp();
        getManagerLoginToken($permissionName, $s);
    }
}

