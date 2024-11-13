<?php
function getInputManagerLoginToken()
{
    // print_r($_POST);
    $name = "inputManagerLoginToken";
    $desc = "M Login Token";
    // print_r(getPostData2());
    if (!isset(getPostData2Encrypted()[$name])) {
        $ar = "{$desc}_NOT_FOUND";
        $en = "{$desc}_NOT_FOUND";
        exitFromScript($ar, $en);
    }
    $value = getPostData2()[$name];
    // checkIfNumber($value);
    if (strlen($value) < 10) {
        $ar = "{$desc}MUST_BE_MOre";
        $en = "{$desc}MUST_BE_More";
        exitFromScript($ar, $en);
    }
    return $value;
}