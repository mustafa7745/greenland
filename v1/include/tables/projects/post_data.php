<?php
function getInputProjectNumber()
{
    $name = "inputProjectNumber";
    $desc = "Project Number";
    if (!isset(getPostData2()[$name])) {
        $ar = "{$desc}_NOT_FOUND";
        $en = "{$desc}_NOT_FOUND";
        exitFromScript($ar, $en);
    }
    $value = getPostData2()[$name];
    // checkIfNumber($value);
    if (strlen($value) < 1) {
        $ar = "{$desc}MUST_BE_MOre";
        $en = "{$desc}MUST_BE_More";
        exitFromScript($ar, $en);
    }
    return $value;
}
function getInputProjectPassword()
{
    $name = "inputProjectPassword";
    $desc = "Project Password";
    if (!isset(getPostData2()[$name])) {
        $ar = "{$desc}_NOT_FOUND";
        $en = "{$desc}_NOT_FOUND";
        exitFromScript($ar, $en);
    }
    $value = getPostData2()[$name];
    checkIfNumber($value);
    if (strlen($value) < 5) {
        $ar = "{$desc}MUST_BE_5";
        $en = "{$desc}MUST_BE_5";
        exitFromScript($ar, $en);
    }
    return $value;
}
