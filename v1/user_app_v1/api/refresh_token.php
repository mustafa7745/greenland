<?php
require_once __DIR__ . '/../../include/login/index_v1.php';
require_once __DIR__ . '/../../include/check/login_tokens/helper.php';
use function Check\getLoginTokensHelper;


class ThisClass
{
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
  function main(): string
  {
    shared_execute_sql("START TRANSACTION");
    $runApp = getMainRunApp();
    $userLoginToken = $this->refreshUserLoginToken($runApp, getRemainedMinute());
    shared_execute_sql("COMMIT");
    return json_encode(array("token" => $userLoginToken->loginToken, "expire_at" => $userLoginToken->expireAt));
  }
}

$this_class = new ThisClass();
die($this_class->main());