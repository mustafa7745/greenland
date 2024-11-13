<?php
namespace UserApp;

// To Get RunApp
require_once "../../../include/check/index_v1.php";
// To Get Token
require_once "../../../include/token/index_v1.php";
// To Get Executer
require_once 'executer.php';

class Home
{
    function read()
    {
        // checkPermission("READ_GROUPS");
        // $s = getMainRunApp();
        // getProjectLoginTokenData("RUN_APP", $s);
        return getHomeExecuter()->executeGetData();
    }
    function readWithUser()
    {
        // checkPermission("READ_GROUPS");
        // $s = getMainRunApp();
        // getProjectLoginTokenData("RUN_APP", $s);
        return getHomeExecuter()->executeGetDataWithUser();
    }
    function readWithUser2()
    {
        // checkPermission("READ_GROUPS");
        // $s = getMainRunApp();
        // getProjectLoginTokenData("RUN_APP", $s);
        return getHomeExecuter()->executeGetDataWithUser2();
    }
}


