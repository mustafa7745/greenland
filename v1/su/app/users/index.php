<?php
namespace SU1;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class Users
{
    function read()
    {
        $this->_check("RUN_APP");
        return getUsersExecuter()->executeGetData(getInputUserPhone3());
    }
    function updateStatus()
    {
        $this->_check("RUN_APP");
        return getUsersExecuter()->executeUpdateStatus(getInputUserId());
    }
    private function _check($permissionName)
    {
        $s = getMainRunApp();
        getProjectLoginTokenData($permissionName, $s);
    }

}

