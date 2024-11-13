<?php
namespace SU1;

// To Get RunApp
require_once "../../../include/check/index_v1.php";
// To Get Token
require_once "../../../include/token/index_v1.php";
// To Get Executer
require_once 'executer.php';

class Projects
{
    function read()
    {
        $loginToken = $this->_check();
        return getProjectsExecuter()->executeGetData($loginToken->projectId);
    }
    function updatePassword()
    {
        $loginToken = $this->_check();
        return getProjectsExecuter()->executeUpdatePassword($loginToken->projectId, getInputProjectPassword3());
    }
    function updateDeviceId()
    {
        $loginToken = $this->_check();
        return getProjectsExecuter()->executeUpdateDeviceId($loginToken->projectId, getInputProjectDeviceId());
    }
    function updateLatLong()
    {
        $loginToken = $this->_check();
        return getProjectsExecuter()->executeUpdateLatLong($loginToken->projectId, getInputProjectLatLong());
    }
    function updateDeliveryPrice()
    {
        $loginToken = $this->_check();
        return getProjectsExecuter()->executeUpdatePriceDeliveryPer1km($loginToken->projectId, getInputProjectPricePer1Km());
    }
    function updateUpdateRequestOrderStatus()
    {
        $loginToken = $this->_check();
        return getProjectsExecuter()->executeUpdateRequestOrderStatus($loginToken->projectId);
    }
    function updateRequestOrderMessage()
    {
        $loginToken = $this->_check();
        return getProjectsExecuter()->executeUpdateRequestOrderMessage($loginToken->projectId, getInputRequestOrderMessage());
    }
    function _check()
    {
        $s = getMainRunApp();
        return getProjectLoginTokenData("RUN_APP", $s);
    }
}


