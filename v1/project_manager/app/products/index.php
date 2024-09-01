<?php
namespace Manager;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';

class Products
{



    function readAll()
    {
        return getProductsExecuter()->executeGetAllData();
    }
    function search()
    {
        return getProductsExecuter()->executeGetDataByNumber(getInputProductNumber());
    }



}


