<?php
namespace SU1;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class Notifications
{
    function add()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getNotificationsExecuter()->executeAddData(getInputNotificationTitle(), getInputNotificationDescription());
    }
    function read()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getNotificationsExecuter()->executeGetData();
    }
}

