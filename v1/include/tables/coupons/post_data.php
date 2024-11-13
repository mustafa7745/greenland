<?php 
function getInputCouponId()
{
    $name = "inputCouponId";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputCouponType()
{
    $name = "inputCouponType";
    if (!isset(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputCouponAmount()
{
    $name = "inputCouponAmount";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    // checkIfNumber($value);
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputCouponCode()
{
    $name = "inputCouponCode";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    // checkIfNumber($value);
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputCouponName()
{
    $name = "inputCouponName";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    // checkIfNumber($value);
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}