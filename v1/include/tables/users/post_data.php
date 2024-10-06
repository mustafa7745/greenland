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
    checkLong($value, 9);
    $value = mysqli_real_escape_string(getDB()->conn, $value);
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
    checkLong($value, 9);
    $value = mysqli_real_escape_string(getDB()->conn, $value);
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
    checkLong($value, 10);
    $value = mysqli_real_escape_string(getDB()->conn, $value);
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
    checkLong($value, 30);
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
    checkLong($value, 10);
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
    checkLong($value, 30);
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
    checkLong($value, 30);

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
    checkLong($value, 50);

    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputLocationTypeId()
{

    $name = "inputLocationTypeId";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        return null;
    }
    $value = getPostData3()[$name];
    print_r($value);
    if (is_int($value) == false) {
        return null;
    }
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
    $value = trim($value);
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    checkLong($value, 100);
    return $value;
}

function getInputUserLocationStreet()
{
    $name = "inputUserLocationStreet";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    checkLong($value, 50);

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
    checkLong($value, 100);

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
    checkLong($value, 150);

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
    checkLong($value, 9);

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
    checkLong($value, 200);
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
