<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin:*");
date_default_timezone_set("Asia/Riyadh");
$root = "/v1/include/";
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
    return $_SERVER["DOCUMENT_ROOT"] . "/" . "v1/su/";
}
function getUserPath()
{
    global $path;
    return $_SERVER["DOCUMENT_ROOT"] . "/" . "tv1/user_app/";
}
function getManagerPath()
{
    global $path;
    return $_SERVER["DOCUMENT_ROOT"] . "/" . "v1/project_manager/";
}
function getDeliveryPath()
{
    global $path;
    return $_SERVER["DOCUMENT_ROOT"] . "/" . "v1/delivery_app/";
}

function getRemainedMinute()
{
    $current_time = new DateTime();
    // تحديد وقت نهاية اليوم (الساعة 23:59:59)
    $end_of_day = new DateTime('tomorrow');
    $end_of_day->setTime(0, 0, 0);

    // حساب الفرق بين الوقت الحالي ونهاية اليوم
    $interval = $current_time->diff($end_of_day);

    // تحويل الفرق إلى دقائق
    $minutes_remaining = ($interval->h * 60) + $interval->i;
    return $minutes_remaining;
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








