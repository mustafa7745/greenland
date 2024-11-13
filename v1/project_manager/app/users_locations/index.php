<?php
namespace Manager;

// To Get RunApp
require_once "../../../include/check/index_v1.php";
// To Get Token
require_once "../../../include/token/index_v1.php";
// To Get Executer
require_once 'executer.php';

class UsersLocations
{

    function read()
    {
        $this->_check("RUN_APP");
        return getUsersLocationsExecuter()->executeGetData(getInputUserId());
    }
    function readById()
    {
        $this->_check("RUN_APP");
        return getUsersLocationsExecuter()->executeGetDataById(getInputUserLocationId());
    }
    function add()
    {
        $this->_check("RUN_APP");
        return getUsersLocationsExecuter()->executeAddData(getInputUserId(), "صنعاء", getInputUserLocationStreet(), getInputUserLocationLatLong(), getInputUserLocationNearTo(), getInputUserLocationContactPhone());
    }
    function updateStreet()
    {
        $this->_check("RUN_APP");
        return getUsersLocationsExecuter()->executeUpdateStreet(getInputUserLocationId(), getInputUserLocationStreet());
    }
    function updateUrl()
    {
        $this->_check("RUN_APP");
        return getUsersLocationsExecuter()->executeUpdateUrl(getInputUserLocationId(), getInputUserLocationUrl());
    }
    function updateNearTo()
    {
        $this->_check("RUN_APP");
        return getUsersLocationsExecuter()->executeUpdateNearTo(getInputUserLocationId(), getInputUserLocationNearTo());
    }
    function updateLatLong()
    {
        $this->_check("RUN_APP");
        return getUsersLocationsExecuter()->executeUpdateLatLong(getInputUserLocationId(), getInputUserLocationLatLong());
    }
    function updateContactPhone()
    {
        $this->_check("RUN_APP");
        return getUsersLocationsExecuter()->executeUpdateContactPhone(getInputUserLocationId(), getInputUserLocationContactPhone());
    }
    private function _check($permissionName)
    {
        $s = getMainRunApp();
        getManagerLoginToken($permissionName, $s);
    }


}

