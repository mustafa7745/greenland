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
        $userLocations = getUsersLocationsExecuter()->executeGetData($userId);
        for ($i = 0; $i < count($userLocations); $i++) {
            $userLocations[$i]["deliveryPrice"] = getDeliveryPrice($userLocations[$i]);
        }
        return $userLocations;
    }
    function add()
    {

        $s = getMainRunApp();
        $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getUsersLocationsExecuter()->executeAddData($userId, getInputUserLocationCity(), getInputUserLocationStreet(), getInputUserLocationLatLong(), getInputUserLocationNearTo(), getInputUserLocationContactPhone());
    }
}

