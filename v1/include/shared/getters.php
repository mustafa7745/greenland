<?php
function getId($data)
{
    return $data["id"];
}
function getName($data)
{
    return $data["name"];
}
function getPhoneFromData($data)
{
    return $data["name"];
}
function getUserLocationId($data)
{
    return $data["userLocationId"];
}
function getProjectId($data)
{
    return $data["projectId"];
}
function getVersion($data)
{
    return $data["version"];
}
function getPackageName($data)
{
    return $data["packageName"];
}
function getIpCount($data)
{
    return $data["ipCount"];
}
function getDeviceCount($data)
{
    return $data["deviceCount"];
}
function getDevice($data)
{
    return $data["device"];
}
function getApp($data)
{
    return $data["app"];
}
function getPermission($data)
{
    return $data["permission"];
}
function getUser($data)
{
    return $data["user"];
}
function getUserSession($data)
{
    return $data["userSession"];
}
function getUserSessionId($data)
{
    return $data["userSessionId"];
}
function getDeliveryManId($data)
{
    return $data["deliveryManId"];
}
function getOrderDeliveryId($data)
{
    return $data["orderDeliveryId"];
}
function getUserId($data)
{
    return $data["userId"];
}
function getRunApp($data)
{
    return $data["runApp"];
}
function getDeviceSession($data)
{
    return $data["deviceSession"];
}
function getDeviceSessionId($data)
{
    return $data["deviceSessionId"];
}
function getDeviceSessionIp($data)
{
    return $data["deviceSessionIp"];
}
function getGroupId($data)
{
    return $data["groupId"];
}
function getIp()
{
    return getenv("REMOTE_ADDR");
}
function getAppDeviceToken($data)
{
    return $data["appToken"];
}
function getExpireAt($data)
{
    return $data["expireAt"];
}
function getUpdatedAt($data)
{
    return $data["updatedAt"];
}
function getCreatedAt($data)
{
    return $data["createdAt"];
}
function getLastLoginAt($data)
{
    return $data["lastLoginAt"];
}
function getLoginToken($data)
{
    return $data["loginToken"];
}
function getToken($data)
{
    return $data["token"];
}
function getPublicKey($data)
{
    return $data["publicKey"];
}
function getPhone($data)
{
    $name = "phone";
    if (!isset($data["phone"])) {
        $ar = "{$name}_NOT_FOUND";
        $en = "{$name}_NOT_FOUND";
        exitFromScript($ar, $en);
    }
    $s = $data["phone"];

    if (strlen($s) != 9) {
        $ar = "MUST_BE_9";
        $en = "MUST_BE_9";
        exitFromScript($ar, $en);
    }
    return $s;
}

// 
function getTag()
{
    $name = "tag";
    if (!isset(getPostData3()[$name]) || empty(getPostData3()[$name])) {
        $ar = "TAG_EMPTY_OR_NOT_FOUND";
        $en = "TAG_EMPTY_OR_NOT_FOUND";
        exitFromScript($ar, $en);
    }
    $value = getPostData3()[$name];
    return $value;
}
function getFrom()
{
    $name = "from";
    if (!isset(getPostData3()[$name])) {
        $ar = "FROM_EMPTY_OR_NOT_FOUND";
        $en = "FROM_EMPTY_OR_NOT_FOUND";
        exitFromScript($ar, $en);
    }
    $value = getPostData3()[$name];
    return $value;
}
function getLimit()
{
    $name = "limit";
    if (!isset(getPostData3()[$name])) {
        return 20;
    }
    $value = getPostData3()[$name];
    if (!is_int($value)) {
        return 20;
    }
    if (strlen($value) > 2) {
        return 20;
    }
    return $value;
}

function getOrderedType()
{
    $name = "orderType";
    if (!isset(getPostData3()[$name])) {
        return 'desc';
    }
    $value = getPostData3()[$name];
    if ($value == 'asc' || $value == 'desc') {
        return $value;
    }
    return 'desc';
}

function getPriceDeliveryPer1Km($data)
{
    return $data["priceDeliveryPer1km"];
}

function getPrice($data)
{
    return $data["price"];
}
function getIsWithOrder($data)
{
    return $data["isWithOrder"];
}