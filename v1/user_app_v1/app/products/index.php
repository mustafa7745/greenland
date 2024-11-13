<?php
namespace UserApp;

// To Get RunApp
require_once "../../../include/check/index_v1.php";
// To Get Token
require_once "../../../include/token/index_v1.php";
// To Get Executer
require_once 'executer.php';

class Products
{
    function read()
    {
        require_once __DIR__ . "/../categories/sql.php";
        return getProductsExecuter()->executeGetData(getInputCategoryId());
    }
    function search()
    {
        return getProductsExecuter()->executeSearchData(getInputProductName());
    }
}


