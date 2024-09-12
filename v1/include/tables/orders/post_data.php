<?php 
function getInputOrderCode()
{
    $name = "inputOrderCode";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    checkIfNumber($value);
    return $value;
}
function getInputOrderStatusId()
{
    $name = "inputOrderStatusId";
    if (!isset(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    checkIfNumber($value);
    return $value;
}