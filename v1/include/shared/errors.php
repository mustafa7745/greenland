<?php

function ERROR_SQL_COMMAND($db)
{
    $s = mysqli_error($db->conn);
    $r = null;
    if ($s) {
        $r = $s;
    } else
        $r = "خطأ في الاوامر";
    $ar = $r;
    $en = $r;
    exitFromScript($ar, $en);
}
function EXP_SQL($e)
{
    if (str_contains($e, "Duplicate entry")) {
        $ar = "DATA_ENTERD_BEFORE";
        $en = "DATA_ENTERD_BEFORE";
        exitFromScript($ar, $en);
    }
    if (str_contains($e, "Cannot delete or update a parent")) {
        $ar = "لايمكن الحذف او التعديل في حال وجود بيانات";
        $en = "CONNOT_DELETE_OR_UPDATE_WHEN_HAVE_ITEMS";
        exitFromScript($ar, $en);
    }
    $ar = "EXP_SQL";
    $en = "EXP_SQL" . $e;
    exitFromScript($ar, $en);
}

function PERMISSION_IS_BLOCKED_FROM_USE_IN($permission_name, $place)
{
    $ar = "{$permission_name}_PERMISSION_IS_BLOCKED_FROM_USE_IN_THIS_{$place}";
    $en = "{$permission_name}_PERMISSION_IS_BLOCKED_FROM_USE_IN_THIS_{$place}";
    exitFromScript($ar, $en);
}
// 
function POST_DATA_NOT_FOUND($num, $code)
{
    $ar = "DATA" . $num . "_NOT_FOUND";
    $en = "DATA" . $num . "_NOT_FOUND";
    exitFromScript($ar, $en);
}

function JSON_FORMAT_INVALID($data = "", $code = 0)
{
    $ar = "JSON_FORMAT_INVALID_" . $data;
    $en = "JSON_FORMAT_INVALID_" . $data;
    ;
    exitFromScript($ar, $en);
}

function P_BLOCKED($permission_name)
{
    $ar = $permission_name . "_BLOCKED";
    $en = $permission_name . "_BLOCKED";

    exitFromScript($ar, $en);
}
function UNKOWN_TAG()
{
    $ar = "UNKOWN_TAG";
    $en = "UNKOWN_TAG";

    exitFromScript($ar, $en);
}
function checkIfNumber($value)
{
    if (!is_numeric($value)) {
        $ar = "MUST_BE_NUMBER";
        $en = "MUST_BE_NUMBER";
        exitFromScript($ar, $en);
    }

}

function FAIL_WHEN_ADD_FILE()
{
    $ar = "FAIL_WHEN_ADD_FILE";
    $en = "FAIL_WHEN_ADD_FILE";

    exitFromScript($ar, $en);
}

function FAIL_WHEN_SEND_MESSAGE()
{
    $ar = "FAIL_WHEN_SEND_MESSAGE_ERROR_SERVICE_ACCOUNT_OR_NETWORK";
    $en = "FAIL_WHEN_SEND_MESSAGE_ERROR_SERVICE_ACCOUNT_OR_NETWORK";

    exitFromScript($ar, $en);
}


function IMAGE_SELECTED_MUST_BE_ONE()
{
    $ar = "IMAGE_SELECTED_MUST_BE_ONE";
    $en = "IMAGE_SELECTED_MUST_BE_ONE";

    exitFromScript($ar, $en);
}
function PAYMENT_VOUCHER_NOT_VALID()
{
    $ar = "PAYMENT_VOUCHER_NOT_VALID";
    $en = "PAYMENT_VOUCHER_NOT_VALID";

    exitFromScript($ar, $en);
}

function CANNOT_DELETE_WHEN_HAVE_ITEMS()
{
    $ar = "CANNOT_DELETE_WHEN_HAVE_ITEMS";
    $en = "CANNOT_DELETE_WHEN_HAVE_ITEMS";

    exitFromScript($ar, $en);
}

function EMPTY_OR_NOT_FOUND($name)
{
    $ar = "INPUT_{$name}_EMPTY_OR_NOT_FOUND";
    $en = "INPUT_{$name}_EMPTY_OR_NOT_FOUND";
    exitFromScript($ar, $en);
}
function LONG_TEXT()
{
    $ar = "LONG TEXT";
    $en = "LONG TEXT";
    exitFromScript($ar, $en);
}