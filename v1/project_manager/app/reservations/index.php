<?php
namespace Manager;

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
        $this->_check("RUN_APP");
        return getReservationsExecuter()->executeGetData(getInputUserPhone3());
    }
    private function _check($permissionName)
    {
        $s = getMainRunApp();
        getManagerLoginToken($permissionName, $s);
    }
}

