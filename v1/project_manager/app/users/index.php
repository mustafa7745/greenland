<?php
namespace Manager;

// To Get RunApp
require_once "../../../include/check/index_v1.php";
// To Get Token
require_once "../../../include/token/index_v1.php";
// To Get Executer
require_once 'executer.php';

class Users
{
    function read()
    {
        $this->_check("RUN_APP");
        return getUsersExecuter()->executeGetData(getInputUserPhone3());
    }
    function readById()
    {
        $this->_check("RUN_APP");
        return getUsersExecuter()->executeGetDataById(getInputUserId());
    }
    function add()
    {
        $loginToken = $this->_check("RUN_APP");
        return getUsersExecuter()->executeAddData(getInputUserName(), getInputUserPhone3(), $loginToken->managerId);
    }
    function updateName()
    {
        $this->_check("RUN_APP");
        return getUsersExecuter()->executeUpdateName(getInputUserId(), getInputUserName());
    }
    function updatePassword()
    {
        $this->_check("RUN_APP");
        return getUsersExecuter()->executeUpdatePassword(getInputUserId(), getInputUserPassword3());

    }
    private function _check($permissionName)
    {
        $s = getMainRunApp();
        return getManagerLoginToken($permissionName, $s);
    }
}

