<?php
namespace Manager;

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
        // $s = getMainRunApp();
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getUsersLocationsExecuter()->executeGetData(getInputUserId());
    }
    function add()
    {
        // $s = getMainRunApp();
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getUsersLocationsExecuter()->executeAddData(getInputUserId(), "صنعاء", getInputUserLocationStreet(), getInputUserLocationLatLong(), getInputUserLocationNearTo(), getInputUserLocationContactPhone());
    }
    function updateStreet()
    {
        // $s = getMainRunApp();
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getUsersLocationsExecuter()->executeUpdateStreet(getInputUserLocationId(), getInputUserLocationStreet());
    }
    function updateUrl()
    {
        // $s = getMainRunApp();
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getUsersLocationsExecuter()->executeUpdateUrl(getInputUserLocationId(), getInputUserLocationUrl());
    }
    function updateNearTo()
    {
        // $s = getMainRunApp();
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getUsersLocationsExecuter()->executeUpdateStreet(getInputUserLocationId(), getInputUserLocationNearTo());
    }
    function updateLatLong()
    {
        // $s = getMainRunApp();
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getUsersLocationsExecuter()->executeUpdateLatLong(getInputUserLocationId(), getInputUserLocationLatLong());
    }
    function updateContactPhone()
    {
        // $s = getMainRunApp();
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getUsersLocationsExecuter()->executeUpdateStreet(getInputUserLocationId(), getInputUserLocationContactPhone());
    }

}

