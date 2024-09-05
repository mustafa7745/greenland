<?php
function getInputOfferQuantity()
{
    $name = "inputOfferQuantity";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    return $value;
}

function getInputOrderOfferId()
{
    $name = "inputOrderOfferId";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    return $value;
}
function getInputOrderOffersIdsWithQnt()
{
    $name = "inputOrderOffersIdsWithQnt";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    return $value;
}