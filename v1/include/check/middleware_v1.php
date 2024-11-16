<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin:*");
date_default_timezone_set("Asia/Riyadh");
// $root = "/v1/include/";
// $path = $_SERVER["DOCUMENT_ROOT"] . "/$root/";

require_once __DIR__ . "/../shared/shared_executer.php";
require_once __DIR__ . "/../shared/shared_sql.php";
require_once __DIR__ . "/../shared/errors.php";
require_once __DIR__ . "/../shared/helper_functions.php";
require_once __DIR__ . "/../database_connection/database.php";
require_once __DIR__ . "/../post_data/post_data_v1.php";
require_once __DIR__ . "/../shared/getters.php";


function getRemainedMinute()
{
    $end_of_day = new DateTime('tomorrow');
    $end_of_day->setTime(0, 0, 0);
    $end_of_day->modify('-1 second');
    $date = $end_of_day->format('Y-m-d H:i:s');
    return $date;
}

$PROJECT_ID = 1;

$USER_ANDROID_APP = 2;
$DELIVERY_ANDROID_APP = 3;

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








