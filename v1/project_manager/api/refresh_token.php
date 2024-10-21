<?php
require_once "../../include/login/index.php";

class ThisClass
{
  function main(): string
  {
    shared_execute_sql("START TRANSACTION");
    // $login = login();
    $runApp = getMainRunApp();
    exitFromScript(getRemainedMinute(), "");
    $managerLoginToken = $this->refreshManagerLoginToken($runApp, getRemainedMinute());

    $data2 = json_encode(array("token" => $managerLoginToken->token, "expire_at" => $managerLoginToken->expireAt));
    $encryptedData = encrypt($data2, getPublicKeyFormat($runApp->device->publicKey));
    shared_execute_sql("COMMIT");
    return json_encode(
      array(
        "encrypted_data" => $encryptedData
      )
    );
  }
  function refreshManagerLoginToken($runApp, $loginTokenDuration = 1)
  {
 
    require_once __DIR__ . '/../../include/check/managers_login_tokens/helper.php';
    $helper = Check\getManagersLoginTokensHelper();
    $token = getInputManagerLoginToken();
    $managerLoginToken = $helper->getDataByToken($token);
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
    if ($managerLoginToken == null) {
      INVALID_TOKEN($runApp, $permission);
    } else {
      if (strtotime(getCurruntDate()) > strtotime($managerLoginToken->expireAt)) {
        $loginTokenString = uniqid(rand(), false);
        $expireAt = $loginTokenDuration;
        $managerLoginToken = $helper->updateToken($managerLoginToken->id, $loginTokenString, $expireAt);
      }
    }
    return $managerLoginToken;
  }
}

$this_class = new ThisClass();
die($this_class->main());