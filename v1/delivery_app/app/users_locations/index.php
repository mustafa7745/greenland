<?php
namespace DeliveryMen;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class UsersLocations
{

    function read()
    {
        $loginToken = $this->_check();
        $deliveryManId = $loginToken->deliveryManId;
        return getUsersLocationsExecuter()->executeGetData(getInputUserLocationId());
    }
    private function _check()
    {
        $s = getMainRunApp();
        return getDeliveryManLoginToken("RUN_APP", $s);
    }
}

