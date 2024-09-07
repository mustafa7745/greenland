<?php


function getInputManagerUserId()
{
    $name = "inputManagerUserId";
    $desc = " Manager User Id";
    if (!isset(getPostData3()[$name])) {
        $ar = "{$desc}_NOT_FOUND";
        $en = "{$desc}_NOT_FOUND";
        exitFromScript($ar, $en);
    }
    $value = getPostData3()[$name];
    
    return $value;
}