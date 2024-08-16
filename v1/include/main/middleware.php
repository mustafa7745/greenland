<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin:*");
date_default_timezone_set("Asia/Riyadh");
$serverPath = $_SERVER["DOCUMENT_ROOT"];
$includePath = "test2/v1/include/";
// $root = ;
$path = $_SERVER["DOCUMENT_ROOT"] . "/$root/";
require_once "{$path}shared/shared_executer.php";
require_once "{$path}shared/shared_sql.php";
require_once "{$path}shared/errors.php";
require_once "{$path}shared/helper_functions.php";
require_once "{$path}database_connection/database.php";
require_once "{$path}post_data/post_data.php";
require_once "{$path}shared/getters.php";

function getPath()
{
    global $path;
    return $path;
}
function getSuPath()
{
    global $path;
    return $_SERVER["DOCUMENT_ROOT"] ."/". "test2/v1/su/";
}
function getUserPath()
{
    global $path;
    return $_SERVER["DOCUMENT_ROOT"] ."/". "test2/v1/user_app/";
}

// 
$BLOCK_ID = 1;
$ALLOW_ID = 2;
$BLOCK_ALL_ID = 3;
$PERMISSION_UNDER_MAINTENANCE = 4;
$PERMISSION_REQUIRED_UPDATE = 5;
// s
$LEVEL_APPS = 1;
$LEVEL_DEVICES = 2;
$LEVEL_IPS = 3;
$LEVEL_DEVICES_SESSION = 4;
$LEVEL_USERS = 5;
// folder names

// Getters








