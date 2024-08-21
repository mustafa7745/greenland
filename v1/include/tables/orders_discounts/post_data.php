<?php 
function getInputOrderDiscountId()
{
    $name = "inputOrderDiscountId";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputOrderDiscountType()
{
    $name = "inputOrderDiscountType";
    if (!isset(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputOrderDiscountAmount()
{
    $name = "inputOrderDiscountAmount";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    // checkIfNumber($value);
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}