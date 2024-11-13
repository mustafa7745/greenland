<?php
function getInputProjectNumber()
{
    $name = "inputProjectNumber";
    $desc = "Project Number";
    if (!isset(getPostData2Encrypted()[$name])) {
        $ar = "{$desc}_NOT_FOUND";
        $en = "{$desc}_NOT_FOUND";
        exitFromScript($ar, $en);
    }
    $value = getPostData2Encrypted()[$name];
    // checkIfNumber($value);
    if (strlen($value) < 1) {
        $ar = "{$desc}MUST_BE_MOre";
        $en = "{$desc}MUST_BE_More";
        exitFromScript($ar, $en);
    }
    return $value;
}
function getInputProjectPassword()
{
    $name = "inputProjectPassword";
    $desc = "Project Password";
    if (!isset(getPostData2Encrypted()[$name])) {
        $ar = "{$desc}_NOT_FOUND";
        $en = "{$desc}_NOT_FOUND";
        exitFromScript($ar, $en);
    }
    $value = getPostData2Encrypted()[$name];
    checkIfNumber($value);
    if (strlen($value) < 5) {
        $ar = "{$desc}MUST_BE_5";
        $en = "{$desc}MUST_BE_5";
        exitFromScript($ar, $en);
    }
    return $value;
}
function getInputProjectPassword3()
{
    $name = "inputProjectPassword";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    if (strlen($value) > 50) {
        LONG_TEXT();
    }
    return $value;
}
function getInputProjectDeviceId()
{
    $name = "inputProjectDeviceId";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    if (strlen($value) > 50) {
        LONG_TEXT();
    }
    return $value;
}
function getInputProjectPricePer1Km()
{
    $name = "inputProjectPricePer1Km";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    if (strlen($value) > 50) {
        LONG_TEXT();
    }
    return $value;
}
function getInputRequestOrderMessage()
{
    $name = "inputRequestOrderMessage";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    if (strlen($value) > 100) {
        LONG_TEXT();
    }
    return $value;
}
function getInputProjectLatLong()
{
    $name = "inputProjectLatLong";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    if (strlen($value) > 100) {
        LONG_TEXT();
    }
    return $value;
}

