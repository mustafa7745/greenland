<?php
namespace SU1;

// To Get RunApp
require_once "../../../include/check/index_v1.php";
// To Get Token
require_once "../../../include/token/index_v1.php";
// To Get Executer
require_once 'executer.php';
require_once __DIR__ . "/../categories/helper.php";

class ProductsGroups
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
        return getProductsGroupsExecuter()->executeGetData();
    }
    function add()
    {
        // checkPermission("READ_GROUPS"); 
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getProductsGroupsExecuter()->executeAddData(getInputCategoryId(), getInputProductGroupName());
    }
}


