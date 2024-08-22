<?php
function getInputAdsId()
{
    $name = "inputAdsId";
    if (!isset(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    return $value;
}
function getInputAdsIsEnabled()
{
    $name = "inputAdsIsEnabled";
    if (!isset(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    return $value;
}

function getInputAdsDescription()
{
    $name = "inputAdsDescription";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    return $value;
}
function getInputAdsImage()
{
    $name = "inputAdsImage";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    return $value;
}