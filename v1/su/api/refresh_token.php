<?php
require_once __DIR__ . '/../../include/login/index.php';
require_once __DIR__ . '/../../include/check/projects_login_tokens/helper.php';

use function Check\getProjectsLoginTokensHelper;


class ThisClass
{
  function main(): string
  {
    shared_execute_sql("START TRANSACTION");
    $runApp = getMainRunApp();
    // print_r($runApp);
    $projectLoginToken = $this->refreshProjectLoginToken($runApp, 10);
    $data2 = json_encode(
      array("token" => $projectLoginToken->token, "expire_at" => $projectLoginToken->expireAt)
    );
    $encryptedData = encrypt($data2, getPublicKeyFormat($runApp->device->publicKey));
    shared_execute_sql("COMMIT");
    return json_encode(
      array(
        "encrypted_data" => $encryptedData
      )
    );
  }
  function refreshProjectLoginToken($runApp, $loginTokenDuration = 1)
  {
    $token = getInputProjectLoginToken();
    $projectLoginToken = getProjectsLoginTokensHelper()->getDataByToken($token);
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
    if ($projectLoginToken == null) {
      INVALID_TOKEN($runApp, $permission);
    } else {
      if (strtotime(getCurruntDate()) > strtotime($projectLoginToken->expireAt)) {
        $loginTokenString = uniqid(rand(), false);
        $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
        $projectLoginToken = getProjectsLoginTokensHelper()->updateToken($projectLoginToken->id, $loginTokenString, $expireAt);
      }
    }
    return $projectLoginToken;
  }
}

$this_class = new ThisClass();
die($this_class->main());