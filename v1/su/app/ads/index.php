<?php
namespace SU1;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class Ads
{

    function read()
    {
        // checkPermission("READ_GROUPS");
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getAdsExecuter()->executeGetData();
    }
    function add()
    {
        // checkPermission("READ_GROUPS");
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getAdsExecuter()->executeAddData(getInputAdsDescription(), getInputAdsImage());
    }

    function updateImage()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getAdsExecuter()->executeUpdateImage(getInputAdsId(), getInputAdsImage());
    }
    function updateDescription()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getAdsExecuter()->executeUpdateDescription(getInputAdsId(), getInputAdsDescription());
    }

    function updateIsEnabled()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getAdsExecuter()->executeUpdateIsEnabled(getInputAdsId());
    }


}


