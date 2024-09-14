<?php
function getInputProductName()
{
    $name = "inputProductName";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    $value = mysqli_real_escape_string(getDB()->conn, $value);
    if (!isArabicOnly($value)) {
        exitFromScript("يحب ان يحتوي النص على حروف عربية فقط", "");
    }
    if (strlen($value) > 50) {
        LONG_TEXT();
    }
    return $value;
}
function isArabicOnly($input)
{
    // تعبير نمطي للتأكد من أن النص يحتوي فقط على حروف عربية
    $pattern = '/^[\p{Arabic}\s]+$/u';

    // preg_match يتحقق مما إذا كان النص يتطابق مع النمط
    return preg_match($pattern, $input);
}
function getInputProductNumber()
{
    $name = "inputProductNumber";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    return $value;
}
function getInputProductPostPrice()
{
    $name = "inputProductPostPrice";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    return $value;
}
function getInputProductPrePost()
{
    $name = "inputProductPrePost";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    return $value;
}
function getInputProductOrder()
{
    $name = "inputProductOrder";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    checkIfNumber($value);
    return $value;
}
function getInputProductGroupNo()
{
    $name = "inputProductGroupNo";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    checkIfNumber($value);
    return $value;
}

function getInputProductId()
{
    $name = "inputProductId";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        EMPTY_OR_NOT_FOUND($name);
    }
    $value = getPostData3()[$name];
    checkIfNumber($value);
    return $value;
}
function getInputProductGroupId()
{
    $name = "inputProductGroupId";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        // EMPTY_OR_NOT_FOUND($name);
        return null;
    }
    $value = getPostData3()[$name];
    checkIfNumber($value);
    return $value;
}
