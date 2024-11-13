<?php
namespace SU1;

// To Get RunApp
require_once "../../../include/check/index_v1.php";
// To Get Token
require_once "../../../include/token/index_v1.php";
// To Get Executer
require_once 'executer.php';

class Categories
{
    function read()
    {
        // checkPermission("READ_GROUPS");
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getCategoriesExecuter()->executeGetData();
    }
    function search()
    {
        // checkPermission("READ_GROUPS");
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getCategoriesExecuter()->executeSearchData(getInputCategoryName());
    }
    function add()
    {
        // checkPermission("READ_GROUPS");
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getCategoriesExecuter()->executeAddData(getInputCategoryName(), getInputCategoryImage());
    }
    function updateName()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getCategoriesExecuter()->executeUpdateName(getInputCategoryId(), getInputCategoryName());
    }
    function updateImage()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getCategoriesExecuter()->executeUpdateImage(getInputCategoryId(), getInputCategoryImage());
    }

    function updateOrder()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getCategoriesExecuter()->executeUpdateOrder(getInputCategoryId(), getInputCategoryOrder());
    }
    function delete()
    {
        $s = getMainRunApp();
        getProjectLoginTokenData("RUN_APP", $s);
        return getCategoriesExecuter()->executeDeleteData(getIds());
    }
}


