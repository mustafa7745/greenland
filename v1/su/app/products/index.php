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


    function updateName($id, $newValue)
    {
        $resultData = $this->check->check("UPDATE_GROUP_NAME");
        checkProjectIdSU($resultData);
        return getAppsExecuter()->executeUpdateName($resultData, $id, $newValue);
    }

    function updateSha($id, $newValue)
    {
        $resultData = $this->check->check("UPDATE_GROUP_NAME");
        checkProjectIdSU($resultData);
        return getAppsExecuter()->executeUpdateSha($resultData, $id, $newValue);
    }
    function updateVersion($id, $newValue)
    {
        $resultData = $this->check->check("UPDATE_GROUP_NAME");
        checkProjectIdSU($resultData);
        return getAppsExecuter()->executeUpdateVersion($resultData, $id, $newValue);
    }

    function search($search)
    {
        $resultData = $this->check->check("ADD_GROUP");
        checkProjectIdSU($resultData);
        return getAppsExecuter()->executeSearchData($search);
    }



}


