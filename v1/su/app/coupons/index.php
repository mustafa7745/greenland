<?php
namespace SU1;

// To Get RunApp
require_once "../../../include/check/index_v1.php";
// To Get Token
require_once "../../../include/token/index_v1.php";
// To Get Executer
require_once 'executer.php';

class Coupons
{
    function read()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        $coupons = getCouponsExecuter()->executeGetData();
        return $coupons;
    }
}

