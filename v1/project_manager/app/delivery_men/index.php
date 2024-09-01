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
        // $s = getMainRunApp();
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getDeliveryMenHelper()->getDataByUserPhone(getInputUserPhone3());
    }
    function readById()
    {
        // $s = getMainRunApp();
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getDeliveryMenHelper()->getDataById2(getInputDeliveryManId());
    }
}

