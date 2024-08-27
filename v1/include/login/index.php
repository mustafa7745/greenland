<?php
require_once "../../include/check/index.php";
require_once $path . 'check/projects/helper.php';
require_once $path . 'check/users_sessions/helper.php';
require_once $path . 'check/login_tokens/helper.php';
require_once $path . 'check/projects_login_tokens/helper.php';


use function Check\getProjectsHelper;
use function Check\getUsersHelper;
use function Check\getUsersSessionsHelper;
use function Check\getLoginTokensHelper;
use function Check\getProjectsLoginTokensHelper;

function loginAll()
{
    $runApp = getMainRunApp();
    $permissionName = "LOGIN";
    //MUST TRANSFORM
    $permission = getPermissionsHelper()->getDataByName($permissionName);
    getPermissionsGroupsHelper()->getData($permissionName, $permission->id, $runApp->app->groupId);
    $failedCount = getFailedAttempsLogsHelper()->getData($runApp->device->id, $permission->id);
    if (getDeviceCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }
    if (getIpCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }

    $user = getUsersHelper()->getData(getInputUserPhone(), getInputUserPassword());

    if ($user == null) {
        $ar = "اسم المستخدم او كلمة المرور غير صحيحة";
        // $en = "USER_NAME_OR_PASSWORD_ERROR";
        $en = "اسم المستخدم او كلمة المرور غير صحيحة";
        getFailedAttempsLogsHelper()->addData($runApp->deviceSession->id, $permission->id);
        shared_execute_sql("COMMIT");
        exitFromScript($ar, $en);
    }

    $userSession = getUsersSessionsHelper()->getData($user->id, $runApp->deviceSession->id);
    if ($userSession == null) {
    // print_r("mustafa22");

        $userSession = getUsersSessionsHelper()->addData($user->id, $runApp->deviceSession->id);
    }
    // print_r($userSession);
    // print_r("mustafa");

    // print_r($runApp);
    require_once __DIR__ . "/../models/Login.php";
    return new ModelLogin($user, $userSession, $runApp);
    //  ["user" => $user, "userSession" => $userSession, "runApp" => $runApp];
}
function login()
{
    $runApp = (new RunApp())->runApp();
    $permissionName = "LOGIN";
    $permission = getPermissionsHelper()->getDataByName($permissionName);
    getPermissionsGroupsHelper()->getData($permissionName, getId($permission), getGroupId(getApp($runApp)));
    $failedCount = getFailedAttempsLogsHelper()->getData(getId(getDevice($runApp)), getId($permission));
    if (getDeviceCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }
    if (getIpCount($failedCount) > 3) {
        P_BLOCKED($permissionName);
    }

    $user = getUsersHelper()->getData(getInputUserPhone(), getInputUserPassword());

    if ($user == null) {
        $ar = "اسم المستخدم او كلمة المرور غير صحيحة";
        // $en = "USER_NAME_OR_PASSWORD_ERROR";
        $en = "اسم المستخدم او كلمة المرور غير صحيحة";
        getFailedAttempsLogsHelper()->addData(getId(getDeviceSessionIp($runApp)), getId($permission));
        shared_execute_sql("COMMIT");
        exitFromScript($ar, $en);
    }

    $userSession = getUsersSessionsHelper()->getData(getId($user), getId(getDeviceSession($runApp)));
    if ($userSession == null) {
        $userSession = getUsersSessionsHelper()->addData(getId($user), getId(getDeviceSession($runApp)));
    }

    return ["user" => $user, "userSession" => $userSession, "permission" => $permission, "runApp" => $runApp];
}
function loginProject($permission, $runApp)
{

    $project = getProjectsHelper()->getData(getInputProjectNumber(), getInputProjectPassword());
    if ($project == null) {
        $ar = ",اسم المستخدم او كلمة المرور غير صحيحة";
        // $en = "USER_NAME_OR_PASSWORD_ERROR";
        $en = ",اسم المستخدم او كلمة المرور غير صحيحة";
        getFailedAttempsLogsHelper()->addData(getId(getDeviceSessionIp($runApp)), getId($permission));
        exitFromScript($ar, $en);
    }
    return $project;
}
function getUserLoginTokenFromUserSession($userSessionId, $loginTokenDuration)
{

    $loginToken = getLoginTokensHelper()->getData($userSessionId);
    if ($loginToken == null) {
        $loginTokenString = uniqid(rand(), false);
        $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
        $loginToken = getLoginTokensHelper()->addData($userSessionId, $loginTokenString, $expireAt);
    } else {

        if (strtotime(getCurruntDate()) > strtotime($loginToken->expireAt)) {
            $loginTokenString = uniqid(rand(), false);
            $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
            $loginToken = getLoginTokensHelper()->updateToken($loginToken->id, $loginTokenString, $expireAt);
        }
    }
    return $loginToken;
}
function getLoginTokenFromUserSessionAndProjectId($userSessionId, $projectId, $loginTokenDuration)
{
    $projectLoginToken = getProjectsLoginTokensHelper()->getData($userSessionId, $projectId);

    if ($projectLoginToken == null) {
        $loginTokenString = uniqid(rand(), false);
        $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
        $projectLoginToken = getProjectsLoginTokensHelper()->addData($userSessionId, $loginTokenString, $projectId, $expireAt);
    } else {
        if (strtotime(getCurruntDate()) > strtotime(getExpireAt($projectLoginToken))) {
            $loginTokenString = uniqid(rand(), false);
            $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
            $projectLoginToken = getProjectsLoginTokensHelper()->updateToken(getId($projectLoginToken), $loginTokenString, $expireAt);
        }
    }
    return $projectLoginToken;
}
// function refreshAndGetProjectLoginToken($runApp, $loginTokenDuration = 1)
// {
//     $token = getInputProjectLoginToken();
//     $projectLoginToken = getProjectsLoginTokensHelper()->getDataByToken($token);
//     $permissionName = "REFRESH_LOGIN_TOKEN";
//     $permission = getPermissionsHelper()->getDataByName($permissionName);
//     getPermissionsGroupsHelper()->getData($permissionName, getId($permission), getGroupId(getApp($runApp)));
//     $failedCount = getFailedAttempsLogsHelper()->getData(getId(getDevice($runApp)), getId($permission));
//     // print_r($failedCount);
//     if (getDeviceCount($failedCount) > 3) {
//         P_BLOCKED($permissionName);
//     }
//     if (getIpCount($failedCount) > 3) {
//         P_BLOCKED($permissionName);
//     }
//     // 
//     if ($projectLoginToken == null) {
//         INVALID_TOKEN($runApp, $permission);
//     } else {
//         if (strtotime(getCurruntDate()) > strtotime(getExpireAt($projectLoginToken))) {
//             $loginTokenString = uniqid(rand(), false);
//             $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
//             $projectLoginToken = getProjectsLoginTokensHelper()->updateToken(getId($projectLoginToken), $loginTokenString, $expireAt);
//         }
//     }
//     return $projectLoginToken;
// }

// function refreshToken($runApp, $loginTokenDuration)
// {
//     $token = getInputLoginToken();
//     $loginToken = getLoginTokensHelper()->getDataByToken($token);
//     $permissionName = "REFRESH_LOGIN_TOKEN";
//     $permission = getPermissionsHelper()->getDataByName($permissionName);
//     getPermissionsGroupsHelper()->getData($permissionName, getId($permission), getGroupId(getApp($runApp)));
//     $failedCount = getFailedAttempsLogsHelper()->getData(getId(getDevice($runApp)), getId($permission));
//     // print_r($failedCount);
//     if (getDeviceCount($failedCount) > 3) {
//         P_BLOCKED($permissionName);
//     }
//     if (getIpCount($failedCount) > 3) {
//         P_BLOCKED($permissionName);
//     }
//     // 
//     if ($loginToken == null) {
//         INVALID_TOKEN($runApp, $permission);
//     } else {
//         if (strtotime(getCurruntDate()) > strtotime(getExpireAt($loginToken))) {
//             $loginTokenString = uniqid(rand(), false);
//             $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
//             $loginToken = getLoginTokensHelper()->updateToken(getId($loginToken), $loginTokenString, $expireAt);
//         }
//     }
//     return $loginToken;
// }

// function INVALID_TOKEN($runApp, $permission)
// {
//     $ar = "ERROR_REFRESH_TOKEN";
//     $en = "ERROR_REFRESH_TOKEN";
//     getFailedAttempsLogsHelper()->addData(getId(getDeviceSessionIp($runApp)), getId($permission));
//     shared_execute_sql("COMMIT");
//     exitFromScript($ar, $en, 400, 5002);
// }

// function checkPermission1($permissionName)
// {
//     // getRunApp()
//     $runApp = getMainRunApp();
//     $permission = getPermissionsHelper()->getDataByName($permissionName);
//     getPermissionsGroupsHelper()->getData($permissionName, getId($permission), getGroupId(getApp($runApp)));
//     $failedCount = getFailedAttempsLogsHelper()->getData(getId(getDevice($runApp)), getId($permission));
//     if (getDeviceCount($failedCount) > 3) {
//         P_BLOCKED($permissionName);
//     }
//     if (getIpCount($failedCount) > 3) {
//         P_BLOCKED($permissionName);
//     }
// }

// $runApp = null;
// function getMainRunApp()
// {
//     global $runApp;
//     if ($runApp == null) {
//         $runApp = (new RunApp())->runApp();
//     }
//     return $runApp;
// }




