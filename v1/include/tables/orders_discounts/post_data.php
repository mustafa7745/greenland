<?php 
function getInputOrderId()
{
    $name = "inputOrderId";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputOrderSystemNumber()
{
    $name = "inputOrderSystemNumber";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputOrderDeliveryId()
{
    $name = "inputOrderDeliveryId";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    // checkIfNumber($value);
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}