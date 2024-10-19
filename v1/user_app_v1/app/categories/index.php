<?php
namespace UserApp;

// To Get RunApp
require_once "../../../include/check/index_v1.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class Categories
{
    function read()
    {
        // checkPermission("READ_GROUPS");
        // $s = getMainRunApp();
        // getProjectLoginTokenData("RUN_APP", $s);
        return getCategoriesExecuter()->executeGetData();
    }
}


