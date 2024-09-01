<?php
namespace Manager;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class Offers
{
    function search()
    {
        return getOffersExecuter()->executeGetDataByName(getInputOfferName());
    }
}


