<?php

function getInputUserPhone()
{
    $name = "inputUserPhone";
    $desc = "User Phone";
    // print_r(getPostData2());
    if (!isset(getPostData2()[$name])) {
        $ar = "{$desc}_NOT_FOUND";
        $en = "{$desc}_NOT_FOUND";
        exitFromScript($ar, $en);
    }
    $value = getPostData2()[$name];
    // checkIfNumber($value);
    if (strlen($value) != 9) {
        $ar = "{$desc}MUST_BE_9";
        $en = "{$desc}MUST_BE_9";
        exitFromScript($ar, $en);
    }
    return $value;
}
function getInputUserPhone3()
{
    $name = "inputUserPhone";
    $desc = "User Phone";
    // print_r(getPostData2());
    if (!isset(getPostData3()[$name])) {
        $ar = "{$desc}_NOT_FOUND";
        $en = "{$desc}_NOT_FOUND";
        exitFromScript($ar, $en);
    }
    $value = getPostData3()[$name];
    // checkIfNumber($value);
    if (strlen($value) != 9) {
        $ar = "{$desc}MUST_BE_9";
        $en = "{$desc}MUST_BE_9";
        exitFromScript($ar, $en);
    }
    return $value;
}
function getInputUserPassword()
{
    $name = "inputUserPassword";
    $desc = "User Password";
    // print_r(getPostData2());
    if (!isset(getPostData2()[$name])) {
        $ar = "{$desc}_NOT_FOUND";
        $en = "{$desc}_NOT_FOUND";
        exitFromScript($ar, $en);
    }
    $value = getPostData2()[$name];
    return $value;
}


function getInputUserLocationId()
{
    $name = "inputUserLocationId";
    // print_r($this->getPostData3()[$name]);
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputUserPassword3()
{
    $name = "inputUserPassword";
    // print_r($this->getPostData3()[$name]);
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputUserLocationUserId()
{
   
    $name = "inputUserLocationUserId";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputUserId()
{
   
    $name = "inputUserId";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}

function getInputUserLocationCity()
{
   
    $name = "inputUserLocationCity";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}


function getInputUserName()
{
   
    $name = "inputUserName";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}

function getInputUserLocationStreet()
{
    $name = "inputUserLocationStreet";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputUserLocationNearTo()
{
    $name = "inputUserLocationNearTo";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputUserLocationLatLong()
{
    $name = "inputUserLocationLatLong";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputUserLocationContactPhone()
{
    $name = "inputUserLocationContactPhone";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputUserLocationUrl()
{
    $name = "inputUserLocationUrl";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
