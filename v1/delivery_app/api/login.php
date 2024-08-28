<?php
// 
require_once __DIR__ . '/../../include/login/index.php';
require_once __DIR__ . '/../../include/check/delivery_men_login_tokens/helper.php';

use function Check\getDeliveryMenLoginTokensHelper;

class ThisClass
{
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
  function main(): string
  {
    shared_execute_sql("START TRANSACTION");
    $login = loginAll();

    $deliveryMan = loginDeliveryMan($login->userSession->userId);
    $deliveryManLoginToken = $this->getLoginTokenFromUserSessionAndDeliveryManId($login->userSession->id, getId($deliveryMan), 1);
    // 
    $data2 = json_encode(array("token" => $deliveryManLoginToken->token, "expire_at" => $deliveryManLoginToken->expireAt));
    $encryptedData = encrypt($data2, getPublicKeyFormat($login->runApp->device->publicKey));
    shared_execute_sql("COMMIT");
    return json_encode(
      array(
        "encrypted_data" => $encryptedData
      )
    );
  }
}

$this_class = new ThisClass();
die($this_class->main());