<?php
namespace SU1;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class Products
{
    function read()
    {
        // checkPermission("READ_GROUPS");
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getProductsExecuter()->executeGetData();
    }
    function add()
    {
        // checkPermission("READ_GROUPS");
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        require_once __DIR__ . '/../categories/sql.php';
        return getProductsExecuter()->executeAddData(getInputCategoryId(), getInputProductName(), getInputProductNumber(), getInputProductPostPrice(), getInputProductImage(), getInputProductGroupId());
    }
    function search()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getProductsExecuter()->executeGetDataByNumber(getInputProductNumber());
    }
    function updateOrder()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getProductsExecuter()->executeUpdateOrder(getInputProductId(), getInputProductOrder());
    }
    function updateName()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getProductsExecuter()->executeUpdateName(getInputProductId(), getInputProductName());
    }
    function updateNumber()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getProductsExecuter()->executeUpdateNumber(getInputProductId(), getInputProductNumber());
    }
    function updateGroup()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getProductsExecuter()->executeUpdateGroup(getInputProductId(), getInputProductGroupId());
    }
    function updatePostPrice()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getProductsExecuter()->executeUpdatePostPrice(getInputProductId(), getInputProductPostPrice());
    }
    function updateAvailable()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getProductsExecuter()->executeUpdateAvailable(getInputProductId());
    }
    function delete()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getProductsExecuter()->executeDeleteData(getIds());
    }
}


