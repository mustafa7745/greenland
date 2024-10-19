<?php
require_once __DIR__ . '/../models/ModelPostData1.php';

$postData1 = null;
function getPostData1(): ModelPostData1
{
  global $postData1;
  if ($postData1 == null) {

    $name1 = "data1";
    if (!isset($_POST[$name1])) {
      POST_DATA_NOT_FOUND(1, 0);
    }
    if (!json_is_validate($_POST[$name1])) {
      $ar = "DATA1_NOT_JSON";
      $en = "DATA1_NOT_JSON";
      exitFromScript($ar, $en);
    }
    $data1 = json_decode($_POST[$name1], TRUE);

    // 1)
    $name = "deviceId";
    if (!isset($data1[$name])) {
      $ar = "{$name}_NOT_FOUND";
      $en = "{$name}_NOT_FOUND";
      exitFromScript($ar, $en);
    }
    $deviceId = $data1[$name];
    if (!ctype_alnum($deviceId)) {
      $ar = $name . "_HAVE_UNKOWN_LETTERS";
      $en = $name . "_HAVE_UNKOWN_LETTERS";
      exitFromScript($ar, $en);
    }
    if (strlen($deviceId) != 16) {
      $ar = $name . "_NOT_16";
      $en = $name . "_NOT_16";
      exitFromScript($ar, $en);
    }
    // 2)
    $name = "deviceInfo";
    if (!isset($data1[$name])) {
      $ar = "{$name}_NOT_FOUND";
      $en = "{$name}_NOT_FOUND";
      exitFromScript($ar, $en);
    }
    checkIsString($data1[$name], $name);
    checkIsJson($data1[$name], $name);
    $deviceInfo = mysqli_escape_string(getDB()->conn, $data1[$name]);
    if (strlen($deviceInfo) > 300) {
      $ar = $name . "_MORE_LONG";
      $en = $name . "_MORE_LONG";
      exitFromScript($ar, $en);
    }
    // 3)
    $name = "appDeviceToken";
    if (!isset($data1[$name])) {
      $ar = "{$name}_NOT_FOUND";
      $en = "{$name}_NOT_FOUND";
      exitFromScript($ar, $en);
    }
    checkIsString($data1[$name], $name);
    $appDeviceToken = mysqli_escape_string(getDB()->conn, $data1[$name]);
    if (strlen($appDeviceToken) > 170) {
      $ar = $name . "_MORE_LONG";
      $en = $name . "_MORE_LONG";
      exitFromScript($ar, $en);
    }
    // 4)
    $name = "appSha";
    if (!isset($data1[$name])) {
      $ar = "{$name}_NOT_FOUND";
      $en = "{$name}_NOT_FOUND";
      exitFromScript($ar, $en);
    }
    checkIsString($data1[$name], $name);
    $appSha = mysqli_escape_string(getDB()->conn, $data1[$name]);
    if (strlen($appSha) > 100) {
      $ar = $name . "_MORE_LONG";
      $en = $name . "_MORE_LONG";
      exitFromScript($ar, $en);
    }
    // 5)
    $name = "devicePublicKey";
    if (!isset($data1[$name])) {
      $ar = "{$name}_NOT_FOUND";
      $en = "{$name}_NOT_FOUND";
      exitFromScript($ar, $en);
    }
    checkIsString($data1[$name], $name);
    $devicePublicKey = mysqli_escape_string(getDB()->conn, $data1[$name]);
    if (strlen($appSha) > 400) {
      $ar = $name . "_MORE_LONG";
      $en = $name . "_MORE_LONG";
      exitFromScript($ar, $en);
    }
    // 6)
    $name = "packageName";

    if (!isset($data1[$name])) {
      $ar = "{$name}_NOT_FOUND";
      $en = "{$name}_NOT_FOUND";
      exitFromScript($ar, $en);
    }
    checkIsString($data1[$name], $name);
    $packageName = mysqli_escape_string(getDB()->conn, $data1[$name]);
    $checked = "com.restaurant.greenland";
    // if ($checked != $packageName) {
    //   if ("com.restaurant.greenland_su_3" != $packageName) {
    //     $ar = "{$name}_NOT_STANDARD";
    //     $en = "{$name}_NOT_STANDARD";
    //     exitFromScript($ar, $en);
    //   }
    //   // $ar = "{$name}_NOT_STANDARD";
    //   // $en = "{$name}_NOT_STANDARD";
    //   // exitFromScript($ar, $en);
    // }
    // if ($checked != $packageName) {
    //   if ("com.delivery.greenland_restaurant1" != $packageName) {
    //     $ar = "{$name}_NOT_STANDARD";
    //     $en = "{$name}_NOT_STANDARD";
    //     exitFromScript($ar, $en);
    //   }
    // $ar = "{$name}_NOT_STANDARD";
    // $en = "{$name}_NOT_STANDARD";
    // exitFromScript($ar, $en);
    // }

    //  

    // 7)
    $name = "appVersion";
    if (!isset($data1[$name])) {
      $ar = "{$name}_NOT_FOUND";
      $en = "{$name}_NOT_FOUND";
      exitFromScript($ar, $en);
    }
    $appVersion = $data1[$name];
    if (!is_int($appVersion)) {
      $ar = $name . "_NOT_NUMBER";
      $en = $name . "_NOT_NUMBER";
      exitFromScript($ar, $en);
    }
    if (strlen($appVersion) > 3) {
      $ar = $name . "_MORE_LONG";
      $en = $name . "_MORE_LONG";
      exitFromScript($ar, $en);
    }
    $postData1 = new ModelPostData1($deviceId, $deviceInfo, $appDeviceToken, $appSha, $devicePublicKey, $packageName, $appVersion);
  }
  return $postData1;
}
function getPostData1_v1(): ModelPostData1
{
  global $postData1;
  if ($postData1 == null) {

    $name1 = "data1";
    if (!isset($_POST[$name1])) {
      POST_DATA_NOT_FOUND(1, 0);
    }
    if (!json_is_validate($_POST[$name1])) {
      $ar = "DATA1_NOT_JSON";
      $en = "DATA1_NOT_JSON";
      exitFromScript($ar, $en);
    }
    $data1 = json_decode($_POST[$name1], TRUE);

    // 1)
    $name = "deviceId";
    if (!isset($data1[$name])) {
      $ar = "{$name}_NOT_FOUND";
      $en = "{$name}_NOT_FOUND";
      exitFromScript($ar, $en);
    }
    $deviceId = $data1[$name];
    if (!ctype_alnum($deviceId)) {
      $ar = $name . "_HAVE_UNKOWN_LETTERS";
      $en = $name . "_HAVE_UNKOWN_LETTERS";
      exitFromScript($ar, $en);
    }
    if (strlen($deviceId) != 16) {
      $ar = $name . "_NOT_16";
      $en = $name . "_NOT_16";
      exitFromScript($ar, $en);
    }
    // 2)
    $name = "deviceInfo";
    if (!isset($data1[$name])) {
      $ar = "{$name}_NOT_FOUND";
      $en = "{$name}_NOT_FOUND";
      exitFromScript($ar, $en);
    }
    checkIsString($data1[$name], $name);
    checkIsJson($data1[$name], $name);
    $deviceInfo = mysqli_escape_string(getDB()->conn, $data1[$name]);
    if (strlen($deviceInfo) > 300) {
      $ar = $name . "_MORE_LONG";
      $en = $name . "_MORE_LONG";
      exitFromScript($ar, $en);
    }
    // 3)
    $name = "appDeviceToken";
    if (!isset($data1[$name])) {
      $ar = "{$name}_NOT_FOUND";
      $en = "{$name}_NOT_FOUND";
      exitFromScript($ar, $en);
    }
    checkIsString($data1[$name], $name);
    $appDeviceToken = mysqli_escape_string(getDB()->conn, $data1[$name]);
    if (strlen($appDeviceToken) > 170) {
      $ar = $name . "_MORE_LONG";
      $en = $name . "_MORE_LONG";
      exitFromScript($ar, $en);
    }
    // 4)
    $name = "appSha";
    if (!isset($data1[$name])) {
      $ar = "{$name}_NOT_FOUND";
      $en = "{$name}_NOT_FOUND";
      exitFromScript($ar, $en);
    }
    checkIsString($data1[$name], $name);
    $appSha = mysqli_escape_string(getDB()->conn, $data1[$name]);
    if (strlen($appSha) > 100) {
      $ar = $name . "_MORE_LONG";
      $en = $name . "_MORE_LONG";
      exitFromScript($ar, $en);
    }
    // 5)
    // $name = "devicePublicKey";
    // if (!isset($data1[$name])) {
    //   $ar = "{$name}_NOT_FOUND";
    //   $en = "{$name}_NOT_FOUND";
    //   exitFromScript($ar, $en);
    // }
    // checkIsString($data1[$name], $name);
    // $devicePublicKey = mysqli_escape_string(getDB()->conn, $data1[$name]);
    // if (strlen($appSha) > 400) {
    //   $ar = $name . "_MORE_LONG";
    //   $en = $name . "_MORE_LONG";
    //   exitFromScript($ar, $en);
    // }
    // 6)
    $name = "packageName";

    if (!isset($data1[$name])) {
      $ar = "{$name}_NOT_FOUND";
      $en = "{$name}_NOT_FOUND";
      exitFromScript($ar, $en);
    }
    checkIsString($data1[$name], $name);
    $packageName = mysqli_escape_string(getDB()->conn, $data1[$name]);
    $checked = "com.restaurant.greenland";
    // if ($checked != $packageName) {
    //   if ("com.restaurant.greenland_su_3" != $packageName) {
    //     $ar = "{$name}_NOT_STANDARD";
    //     $en = "{$name}_NOT_STANDARD";
    //     exitFromScript($ar, $en);
    //   }
    //   // $ar = "{$name}_NOT_STANDARD";
    //   // $en = "{$name}_NOT_STANDARD";
    //   // exitFromScript($ar, $en);
    // }
    // if ($checked != $packageName) {
    //   if ("com.delivery.greenland_restaurant1" != $packageName) {
    //     $ar = "{$name}_NOT_STANDARD";
    //     $en = "{$name}_NOT_STANDARD";
    //     exitFromScript($ar, $en);
    //   }
    // $ar = "{$name}_NOT_STANDARD";
    // $en = "{$name}_NOT_STANDARD";
    // exitFromScript($ar, $en);
    // }

    //  

    // 7)
    $name = "appVersion";
    if (!isset($data1[$name])) {
      $ar = "{$name}_NOT_FOUND";
      $en = "{$name}_NOT_FOUND";
      exitFromScript($ar, $en);
    }
    $appVersion = $data1[$name];
    if (!is_int($appVersion)) {
      $ar = $name . "_NOT_NUMBER";
      $en = $name . "_NOT_NUMBER";
      exitFromScript($ar, $en);
    }
    if (strlen($appVersion) > 3) {
      $ar = $name . "_MORE_LONG";
      $en = $name . "_MORE_LONG";
      exitFromScript($ar, $en);
    }
    $postData1 = new ModelPostData1($deviceId, $deviceInfo, $appDeviceToken, $appSha, "", $packageName, $appVersion);
  }
  return $postData1;
}


$postData2 = null;
function getPostData2()
{
  global $postData2;
  if ($postData2 == null) {
    $name = "data2";
    if (!isset($_POST[$name])) {
      POST_DATA_NOT_FOUND(2, 0);
    }
    if (!is_string($_POST[$name])) {
      $ar = "DATA2_NOT_String";
      $en = "DATA2_NOT_String";
      exitFromScript($ar, $en);
    }
    $data = decrypt($_POST[$name]);
    // print_r($_POST[$name]);

    if ($data == null) {
      $ar = "ERROR_ENCRYPTED";
      $en = "ERROR_ENCRYPTED";
      $code = 1111;
      exitFromScript($ar, $en, 400, $code);
    }
    $postData2 = json_decode($data, true);
  }
  return $postData2;

}

$postData1 = null;
function getPostData3()
{
  global $postData3;
  if ($postData3 == null) {
    $name1 = "data3";
    if (!isset($_POST[$name1])) {
      POST_DATA_NOT_FOUND(3, 0);
    }
    if (!json_is_validate($_POST[$name1])) {
      $ar = "DATA3_NOT_JSON";
      $en = "DATA3_NOT_JSON";
      exitFromScript($ar, $en);
    }
    $postData3 = json_decode($_POST[$name1], TRUE);
  }
  return $postData3;

}
