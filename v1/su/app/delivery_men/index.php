<?php
namespace SU1;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class DeliveryMen
{

    function add()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        require_once __DIR__ . '/../users/sql.php';
        return getDeliveryMenExecuter()->executeAddData(getInputUserId());
    }
    function search()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        require_once __DIR__ . '/../users/sql.php';
        return getDeliveryMenExecuter()->executeGetData(getInputUserId());
    }
}


