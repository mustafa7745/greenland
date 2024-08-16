<?php

function getInputDeliveryManId()
{
    $name = "inputDeliveryManId";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    checkIfNumber($value);
    // $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}