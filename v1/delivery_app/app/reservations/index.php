<?php
namespace DeliveryMen;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class Reservations
{
    // private $check;

    // public function __construct()
    // {
    //     $this->check = new CheckPermission();
    // }

    function read()
    {
        $s = getMainRunApp();
        $modelDeliveryManLoginTokenUserSession = getDeliveryManLoginToken("RUN_APP", $s);
        $deliveryManId = $modelDeliveryManLoginTokenUserSession->modeDeliveryManLoginToken->deliveryManId;
        return getReservationsExecuter()->executeGetData($deliveryManId);
    }
    function add()
    {
        $s = getMainRunApp();
        $modelDeliveryManLoginTokenUserSession = getDeliveryManLoginToken("RUN_APP", $s);
        $deliveryManId = $modelDeliveryManLoginTokenUserSession->modeDeliveryManLoginToken->deliveryManId;
        return getReservationsExecuter()->executeAddData($deliveryManId);
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

