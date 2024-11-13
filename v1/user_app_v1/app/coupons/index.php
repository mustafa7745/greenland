<?php
namespace UserApp;

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
        $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        $coupon = getCouponsExecuter()->executeGetDataByCode(code: getInputCouponCode());
        return $coupon;
    }
}

