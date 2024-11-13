<?php
namespace Manager;

// To Get RunApp
require_once "../../../include/check/index_v1.php";
// To Get Token
require_once "../../../include/token/index_v1.php";
// To Get Executer
require_once 'executer.php';

class Offers
{
    function search()
    {
        $this->_check("RUN_APP");
        return getOffersExecuter()->executeGetDataByName(getInputOfferName());
    }
    private function _check($permissionName)
    {
        $s = getMainRunApp();
        getManagerLoginToken($permissionName, $s);
    }
}


