<?php
require_once __DIR__ . '/../check/index_v1.php';
require_once __DIR__ . '/../check/users_sessions/helper.php';

use function Check\getUsersHelper;
use function Check\getUsersSessionsHelper;

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


