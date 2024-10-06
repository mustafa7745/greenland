<?php
namespace UserApp;
// To Get Executer
require_once "../../../include/check/index.php";

require_once 'executer.php';

class LocationTypes
{
    function read()
    {
        return getLocationTypesExecuter()->executeGetData();
    }
}

