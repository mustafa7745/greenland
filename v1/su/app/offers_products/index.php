<?php
namespace SU1;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class OffersProducts
{

    function read()
    {
        // checkPermission("READ_GROUPS");
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getOffersProductsExecuter()->executeGetData(getInputOfferId());
    }
    function add()
    {
        // checkPermission("READ_GROUPS");
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getOffersProductsExecuter()->executeAddData(getInputOfferId(), getInputProductId(), getInputProductQuantity());
    }


    function updateQuantity()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        require_once __DIR__ . '/../../../include/tables/orders_products/attribute.php';
        return getOffersProductsExecuter()->executeUpdateQuantity(getInputOfferProductId(), getInputProductQuantity());
    }
   

    function delete()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getOffersProductsExecuter()->executeDeleteData(getIds());
    }


}


