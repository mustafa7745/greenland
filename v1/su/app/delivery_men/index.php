<?php
namespace SU1;

// To Get RunApp
require_once "../../../include/check/index_v1.php";
// To Get Token
require_once "../../../include/token/index_v1.php";
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
    function updateStatus()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        require_once __DIR__ . '/../users/sql.php';
        return getDeliveryMenExecuter()->executeUpdateStatus(getInputDeliveryManId());
    }
}


