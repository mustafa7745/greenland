<?php
namespace SU1;

// To Get RunApp
require_once "../../../include/check/index_v1.php";
// To Get Token
require_once "../../../include/token/index_v1.php";
// To Get Executer
require_once 'executer.php';

class Offers
{

    function read()
    {
        // checkPermission("READ_GROUPS");
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getOffersExecuter()->executeGetData();
    }
    function add()
    {
        // checkPermission("READ_GROUPS");
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getOffersExecuter()->executeAddData(getInputOfferName(), getInputOfferDescription(), getInputOfferPrice(), getInputOfferImage(), getInputOfferExpireAt());
    }
    function updateName()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getOffersExecuter()->executeUpdateName(getInputOfferId(), getInputOfferName());
    }
    function updateDescription()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getOffersExecuter()->executeUpdateDescription(getInputOfferId(), getInputOfferDescription());
    }

    function updatePrice()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getOffersExecuter()->executeUpdatePrice(getInputOfferId(), getInputOfferPrice());
    }
    function updateEnabled()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getOffersExecuter()->executeUpdateEnabled(getInputOfferId());
    }


}


