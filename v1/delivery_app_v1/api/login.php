<?php
// 
require_once __DIR__ . '/../../include/login/index_v1.php';
require_once __DIR__ . '/../../include/check/delivery_men_login_tokens/helper.php';

use function Check\getDeliveryMenLoginTokensHelper;

class ThisClass
{
  function main(): string
  {
    shared_execute_sql("START TRANSACTION");
    $login = loginAll();

    $deliveryMan = $this->loginDeliveryMan($login->userSession->userId);
    $deliveryManLoginToken = $this->getLoginTokenFromUserSessionAndDeliveryManId($login->userSession->id, getId($deliveryMan), 1);
    // 
    shared_execute_sql("COMMIT");
    return json_encode(array("token" => $deliveryManLoginToken->token, "expire_at" => $deliveryManLoginToken->expireAt));
  }
  function getLoginTokenFromUserSessionAndDeliveryManId($userSessionId, $deliveryManId, $loginTokenDuration)
  {
    $projectLoginToken = getDeliveryMenLoginTokensHelper()->getData($userSessionId, $deliveryManId);

    if ($projectLoginToken == null) {
      $loginTokenString = uniqid(rand(), false);
      $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
      $projectLoginToken = getDeliveryMenLoginTokensHelper()->addData($userSessionId, $loginTokenString, $deliveryManId, $expireAt);
    } else {
      if (strtotime(getCurruntDate()) > strtotime($projectLoginToken->expireAt)) {
        $loginTokenString = uniqid(rand(), false);
        $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
        $projectLoginToken = getDeliveryMenLoginTokensHelper()->updateToken($projectLoginToken->id, $loginTokenString, $expireAt);
      }
    }
    return $projectLoginToken;
  }
  function loginDeliveryMan($userId)
  {
    require_once __DIR__ . '/../../include/check/delivery_men/helper.php';
    $delivery_man = Check\getDeliveryMenHelper()->getData($userId);
    if ($delivery_man == null) {
      $ar = "هذا المستخدم غير مسجل في التوصيل";
      $en = "not register in deliverty";
      exitFromScript($ar, $en);
    }
    return $delivery_man;
  }

}

$this_class = new ThisClass();
die($this_class->main());