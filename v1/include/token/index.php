<?php
require_once $path . 'check/login_tokens/helper.php';
require_once $path . 'check/projects_login_tokens/helper.php';
require_once $path . 'check/users_sessions/helper.php';
require_once $path . 'models/UserLoginToken.php';
require_once $path . 'models/UserSession.php';
require_once $path . 'models/UserLoginUserSession.php';
require_once $path . 'models/DeliveryManLoginUserSession.php';

// require_once $path . 'models/DeliveryManLoginToken.php';

require_once $path . 'check/delivery_men_login_tokens/helper.php';
require_once $path . 'check/managers_login_tokens/helper.php';








use function Check\getLoginTokensHelper;
use function Check\getProjectsLoginTokensHelper;
use function Check\getUsersSessionsHelper;
use function Check\getDeliveryMenLoginTokensHelper;
use function Check\getManagersHelper;
use function Check\getManagersLoginTokensHelper;




function refreshManagerLoginToken($runApp, $loginTokenDuration = 1)
{
    $helper = getManagersLoginTokensHelper();
    $token = getInputManagerLoginToken();
    $managerLoginToken = $helper->getDataByToken($token);
    $permissionName = "REFRESH_LOGIN_TOKEN";
    $permission = getPermissionsHelper()->getDataByName($permissionName);
    getPermissionsGroupsHelper()->getData($permissionName, getId($permission), getGroupId(getApp($runApp)));
    $failedCount = getFailedAttempsLogsHelper()->getData(getId(getDevice($runApp)), getId($permission));
    // print_r($failedCount);
    if (getDeviceCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }
    if (getIpCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }
    // 
    if ($managerLoginToken == null) {
        INVALID_TOKEN($runApp, $permission);
    } else {
        if (strtotime(getCurruntDate()) > strtotime(getExpireAt($managerLoginToken))) {
            $loginTokenString = uniqid(rand(), false);
            $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
            $managerLoginToken = $helper->updateToken(getId($managerLoginToken), $loginTokenString, $expireAt);
        }
    }
    return $managerLoginToken;
}
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

function refreshUserLoginToken($runApp, $loginTokenDuration)
{
    $token = getInputLoginToken();
    $loginToken = getLoginTokensHelper()->getDataByToken($token);
    $permissionName = "REFRESH_LOGIN_TOKEN";
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
            $loginTokenString = uniqid(rand(), false);
            $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
            $loginToken = getLoginTokensHelper()->updateToken($loginToken->id, $loginTokenString, $expireAt);
        }
    }
    return $loginToken;
}

function getUserLoginToken($permissionName, $runApp)
{
    $token = getInputLoginToken();
    $loginToken = getLoginTokensHelper()->getDataByToken($token);
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


function getManagerLoginToken($permissionName, $runApp)
{
    $token = getInputLoginToken();
    $loginToken = getLoginTokensHelper()->getDataByToken($token);
    // $permissionName = "REFRESH_LOGIN_TOKEN";
    $permission = getPermissionsHelper()->getDataByName($permissionName);
    getPermissionsGroupsHelper()->getData($permissionName, getId($permission), getGroupId(getApp($runApp)));
    $failedCount = getFailedAttempsLogsHelper()->getData(getId(getDevice($runApp)), getId($permission));
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
        if (strtotime(getCurruntDate()) > strtotime(getExpireAt($loginToken))) {
            TOKEN_NEED_UPDATE();
        }
    }
    // 
    $userSession = getUsersSessionsHelper()->getDataById(getUserSessionId($loginToken));
    // 
    $modalUserLoginToken = new ModelUserLoginToken($loginToken);
    $modelUserSession = new ModelUserSession($userSession);
    return new ModelUserLoginUserSession($modalUserLoginToken, $modelUserSession);
}
function getDeliveryManLoginToken($permissionName, $runApp)
{
    $token = getInputDeliveryMenLoginToken();
    $loginToken = getDeliveryMenLoginTokensHelper()->getDataByToken($token);
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
    // $userSession = getUsersSessionsHelper()->getDataById($loginToken->userSessionId);
    // 
    // $modalDeliveryManLoginToken = new ModelDeliveryManLoginToken($loginToken);
    // $modelUserSession = new ModelUserSession($userSession);
    return $loginToken;
}

function INVALID_TOKEN($runApp, $permission)
{
    $ar = "ERROR_REFRESH_TOKEN";
    $en = "ERROR_REFRESH_TOKEN";
    // sleep(5);
    getFailedAttempsLogsHelper()->addData(getId(getDeviceSessionIp($runApp)), getId($permission));
    shared_execute_sql("COMMIT");
    exitFromScript($ar, $en, 400, 5002);
}
function TOKEN_NEED_UPDATE()
{
    $ar = "TOKEN_NEED_UPDATE";
    $en = "TOKEN_NEED_UPDATE";
    exitFromScript($ar, $en, 400, 5001);
}