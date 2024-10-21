<?php
require_once __DIR__ . '/../../include/login/index_v1.php';
require_once __DIR__ . '/../../include/check/delivery_men_login_tokens/helper.php';



use function Check\getDeliveryMenLoginTokensHelper;

class ThisClass
{
  function refreshDeliveryManLoginToken($runApp, $loginTokenDuration = 1)
  {
    $helper = getDeliveryMenLoginTokensHelper();
    $token = getInputDeliveryMenLoginToken();
    $deliveryManLoginToken = $helper->getDataByToken($token);
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
    if ($deliveryManLoginToken == null) {
      INVALID_TOKEN($runApp, $permission);
    } else {
      if (strtotime(getCurruntDate()) > strtotime($deliveryManLoginToken->expireAt)) {
        $loginTokenString = uniqid(rand(), false);
        $expireAt = $loginTokenDuration;
        $deliveryManLoginToken = getDeliveryMenLoginTokensHelper()->updateToken($deliveryManLoginToken->id, $loginTokenString, $expireAt);
      }
    }
    return $deliveryManLoginToken;
  }
  function main(): string
  {
    shared_execute_sql("START TRANSACTION");
    $runApp = getMainRunApp();
    $deliveryManLoginToken = $this->refreshDeliveryManLoginToken($runApp, 1);
    // 
    shared_execute_sql("COMMIT");
    return json_encode(array("token" => $deliveryManLoginToken->token, "expire_at" => $deliveryManLoginToken->expireAt));

  }
}

$this_class = new ThisClass();
die($this_class->main());