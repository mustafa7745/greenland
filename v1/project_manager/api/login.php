<?php
// 
require_once "../../include/login/index.php";
require_once __DIR__ . "/../../include/check/managers_login_tokens/helper.php";
use function Check\getManagersLoginTokensHelper;

class ThisClass
{
  function main(): string
  {
    shared_execute_sql("START TRANSACTION");
    $login = loginAll();
    $manager = $this->loginManager($login->userSession->userId);
    $managerLoginToken = $this->getLoginTokenFromUserSessionAndManagerId($login->userSession->id, getId($manager), getRemainedMinute());
    // 
    $data2 = json_encode(array("token" => $managerLoginToken->token, "expire_at" => $managerLoginToken->expireAt));
    $encryptedData = encrypt($data2, getPublicKeyFormat($login->runApp->device->publicKey));
    shared_execute_sql("COMMIT");
    return json_encode(
      array(
        "encrypted_data" => $encryptedData
      )
    );
  }

  function loginManager($userId)
  {
    require_once __DIR__ . '/../../include/check/managers/helper.php';
    $delivery_man = Check\getManagersHelper()->getData($userId);
    if ($delivery_man == null) {
      $ar = "هذا المستخدم غير مسجل في الادارة";
      $en = "not register in management";
      exitFromScript($ar, $en);
    }
    return $delivery_man;
  }
  function getLoginTokenFromUserSessionAndManagerId($userSessionId, $managerId, $loginTokenDuration)
  {
    $projectLoginToken = getManagersLoginTokensHelper()->getData($userSessionId, $managerId);

    if ($projectLoginToken == null) {
      $loginTokenString = uniqid(rand(), false);
      $expireAt = $loginTokenDuration;
      $projectLoginToken = getManagersLoginTokensHelper()->addData($userSessionId, $loginTokenString, $managerId, $expireAt);
    } else {
      if (strtotime(getCurruntDate()) > strtotime($projectLoginToken->expireAt)) {
        $loginTokenString = uniqid(rand(), false);
        $expireAt = $loginTokenDuration;
        $projectLoginToken = getManagersLoginTokensHelper()->updateToken($projectLoginToken->id, $loginTokenString, $expireAt);
      }
    }
    return $projectLoginToken;
  }

}

$this_class = new ThisClass();
die($this_class->main());