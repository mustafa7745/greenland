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
    // private $check;

    // public function __construct()
    // {
    //     $this->check = new CheckPermission();
    // }

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
        return getProductsExecuter()->executeAddData(getInputCategoryId(), getInputProductName(), getInputProductNumber(), getInputProductPostPrice(), getInputProductImage(), getInputProductGroupId());
    }

    function search()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getProductsExecuter()->executeGetDataByNumber(getInputProductNumber());
    }



}


