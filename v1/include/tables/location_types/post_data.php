<?php

function getInputLocationTypeId()
{

    $name = "inputLocationTypeId";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        return null;
    }
    $value = getPostData3()[$name];
    if (is_int($value) == false) {
        return null;
    }
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}