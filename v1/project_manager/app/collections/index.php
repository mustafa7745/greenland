<?php
namespace Manager;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class Collections
{
    function read()
    {
        $loginToken = $this->_check("RUN_APP");
        return getCollectionsExecuter()->executeGetData(getInputDeliveryManId(), $loginToken->managerId);
    }
    function collect()
    {
        $loginToken = $this->_check("RUN_APP");
        return getCollectionsExecuter()->executeCollectData(getIds(),$loginToken->managerId);
    }
    private function _check($permissionName)
    {
        $s = getMainRunApp();
        return getManagerLoginToken($permissionName, $s);
    }

}


