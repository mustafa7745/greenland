<?php
require_once __DIR__ . '/../../include/login/index_v1.php';
require_once __DIR__ . '/../../include/check/login_tokens/helper.php';
use function Check\getLoginTokensHelper;

class ThisClass
{


  function main(): string
  {
    shared_execute_sql("START TRANSACTION");
    $login = loginAll();
    $userLoginToken = $this->getUserLoginTokenFromUserSession($login->userSession->id, getRemainedMinute());
    shared_execute_sql("COMMIT");
    return json_encode(array("token" => $userLoginToken->loginToken, "expire_at" => $userLoginToken->expireAt));
    
  }
  function getUserLoginTokenFromUserSession($userSessionId, $loginTokenDuration)
  {
    $loginToken = getLoginTokensHelper()->getData($userSessionId);
    if ($loginToken == null) {

      $loginTokenString = uniqid(rand(), false);
      $expireAt = $loginTokenDuration;

      $loginToken = getLoginTokensHelper()->addData($userSessionId, $loginTokenString, $expireAt);
    } else {

      if (strtotime(getCurruntDate()) > strtotime($loginToken->expireAt)) {
        $loginTokenString = uniqid(rand(), false);
        $expireAt = $loginTokenDuration;
        $loginToken = getLoginTokensHelper()->updateToken($loginToken->id, $loginTokenString, $expireAt);
      }
    }
    return $loginToken;
  }
}

$this_class = new ThisClass();
die($this_class->main());