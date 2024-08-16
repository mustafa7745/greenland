<?php
namespace UserApp;

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
        $s = getMainRunApp();
        $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getUsersLocationsExecuter()->executeGetData($userId);
    }
    function add()
    {
        $s = getMainRunApp();
        $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getUsersLocationsExecuter()->executeAddData($userId, getInputUserLocationCity(), getInputUserLocationStreet(), getInputUserLocationLatLong(), getInputUserLocationNearTo(), getInputUserLocationContactPhone());
    }
}

