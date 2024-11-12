<?php
require_once __DIR__ . '/../check/login_tokens/helper.php';
require_once __DIR__ . '/../check/projects_login_tokens/helper.php';
require_once __DIR__ . '/../check/users_sessions/helper.php';
require_once __DIR__ . '/../models/UserLoginToken.php';
require_once __DIR__ . '/../models/UserSession.php';
require_once __DIR__ . '/../models/UserLoginUserSession.php';
require_once __DIR__ . '/../models/DeliveryManLoginUserSession.php';
require_once __DIR__ . '/../check/delivery_men_login_tokens/helper.php';








use function Check\getLoginTokensHelper;
use function Check\getProjectsLoginTokensHelper;
use function Check\getUsersSessionsHelper;
use function Check\getDeliveryMenLoginTokensHelper;
use function Check\getManagersLoginTokensHelper;





function getProjectLoginTokenData($permissionName, $runApp)
{
    $token = getInputProjectLoginToken();
    $projectLoginToken = getProjectsLoginTokensHelper()->getDataByToken($token);
    // 
    $permission = getPermissionsHelper()->getDataByName($permissionName);
    getPermissionsGroupsHelper()->getData($permissionName, $permission->id, $runApp->app->groupId);
    $failedCount = getFailedAttempsLogsHelper()->getData($runApp->device->id, $permission->id);
    // print_r($failedCount);
    if (getDeviceCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }
    if (getIpCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }
    // 
    if ($projectLoginToken == null) {
        INVALID_TOKEN($runApp, $permission);
    } else {
        if (strtotime(getCurruntDate()) > strtotime($projectLoginToken->expireAt)) {
            TOKEN_NEED_UPDATE();
        }
    }
    return $projectLoginToken;
}
function getUserLoginToken($permissionName, $runApp)
{
    $token = getInputLoginToken();
    // print_r($token);
    $loginToken = getLoginTokensHelper()->getDataByToken($token);
    if ($loginToken == null) {
        INVALID_TOKEN2();
    }
    // 
    require_once __DIR__ . '/../../include/check/users_sessions/helper.php';
    $userSession = Check\getUsersSessionsHelper()->getDataById($loginToken->userSessionId);
    require_once __DIR__ . '/../../include/check/users/helper.php';
    $user = Check\getUsersHelper()->getDataById($userSession->userId);
    if ($user->status != '1') {
        USER_DISABLED();
    }
    // $permissionName = "REFRESH_LOGIN_TOKEN";
    $permission = getPermissionsHelper()->getDataByName($permissionName);
    getPermissionsGroupsHelper()->getData($permissionName, $permission->id, $runApp->app->groupId);
    $failedCount = getFailedAttempsLogsHelper()->getData($runApp->device->id, $permission->id);
    // print_r($failedCount);
    if (getDeviceCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }
    if (getIpCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }
    // 
    if ($loginToken == null) {
        INVALID_TOKEN($runApp, $permission);
    } else {
        if (strtotime(getCurruntDate()) > strtotime($loginToken->expireAt)) {
            TOKEN_NEED_UPDATE();
        }
    }
    // 
    $userSession = getUsersSessionsHelper()->getDataById($loginToken->userSessionId);
    return new ModelUserLoginUserSession($loginToken, $userSession);
}
function getDeliveryManLoginToken($permissionName, $runApp)
{
    $token = getInputDeliveryMenLoginToken();
    $loginToken = getDeliveryMenLoginTokensHelper()->getDataByToken($token);

    require_once __DIR__ . '/../../include/check/delivery_men/helper.php';
    $delivery_man = Check\getDeliveryMenHelper()->getDataById($loginToken->deliveryManId);
    if ($delivery_man[Check\getDeliveryMenHelper()->status] != '1') {
        USER_DISABLED();
    }
    // $permissionName = "REFRESH_LOGIN_TOKEN";
    $permission = getPermissionsHelper()->getDataByName($permissionName);
    getPermissionsGroupsHelper()->getData($permissionName, $permission->id, $runApp->app->groupId);
    $failedCount = getFailedAttempsLogsHelper()->getData($runApp->device->id, $permission->id);
    // print_r($failedCount);
    if (getDeviceCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }
    if (getIpCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }
    // 
    if ($loginToken == null) {
        INVALID_TOKEN($runApp, $permission);
    } else {
        if (strtotime(getCurruntDate()) > strtotime($loginToken->expireAt)) {
            TOKEN_NEED_UPDATE();
        }
    }
    return $loginToken;
}

function getManagerLoginToken($permissionName, $runApp)
{
    require_once __DIR__ . '/../check/managers_login_tokens/helper.php';
    $token = getInputManagerLoginToken();
    $loginToken = getManagersLoginTokensHelper()->getDataByToken($token);
    // $permissionName = "REFRESH_LOGIN_TOKEN";
    $permission = getPermissionsHelper()->getDataByName($permissionName);
    getPermissionsGroupsHelper()->getData($permissionName, $permission->id, $runApp->app->groupId);
    $failedCount = getFailedAttempsLogsHelper()->getData($runApp->device->id, $permission->id);
    // print_r($failedCount);
    if (getDeviceCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }
    if (getIpCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }
    // 
    if ($loginToken == null) {
        INVALID_TOKEN($runApp, $permission);
    } else {
        if (strtotime(getCurruntDate()) > strtotime($loginToken->expireAt)) {
            TOKEN_NEED_UPDATE();
        }
    }
    return $loginToken;
}


