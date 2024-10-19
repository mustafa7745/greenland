<?php
namespace UserApp;

// To Get RunApp
require_once "../../../include/check/index_v1.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class Users
{
    function updateName()
    {

        $loginToken = $this->_check();
        return getUsersExecuter()->executeUpdateName($loginToken->modelUserSession->userId, getInputUserName());
    }
    private function _check()
    {
        $s = getMainRunApp();
        return getUserLoginToken("RUN_APP", $s);
    }
}


