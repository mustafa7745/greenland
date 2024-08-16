<?php
namespace Manager;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class Acceptance
{
    // private $check;

    // public function __construct()
    // {
    //     $this->check = new CheckPermission();
    // }

    function read()
    {
        $s = getMainRunApp();
        $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getUsersLocationsExecuter()->executeGetData($userId);
    }
    function add()
    {

        // require_once (getPath() . 'tables/delivery_men/attribute.php');
        // require_once (getPath() . 'tables/orders_delivery/attribute.php');
        // $s = getMainRunApp();
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getAcceptanceExecuter()->executeAddData(getInputDeliveryManId(), getInputOrderDeliveryId());
    }


    function updateName($id, $newValue)
    {
        $resultData = $this->check->check("UPDATE_GROUP_NAME");
        checkProjectIdSU($resultData);
        return getAppsExecuter()->executeUpdateName($resultData, $id, $newValue);
    }

    function updateSha($id, $newValue)
    {
        $resultData = $this->check->check("UPDATE_GROUP_NAME");
        checkProjectIdSU($resultData);
        return getAppsExecuter()->executeUpdateSha($resultData, $id, $newValue);
    }
    function updateVersion($id, $newValue)
    {
        $resultData = $this->check->check("UPDATE_GROUP_NAME");
        checkProjectIdSU($resultData);
        return getAppsExecuter()->executeUpdateVersion($resultData, $id, $newValue);
    }

    function search($search)
    {
        $resultData = $this->check->check("ADD_GROUP");
        checkProjectIdSU($resultData);
        return getAppsExecuter()->executeSearchData($search);
    }



}

