<?php 
// namespace DeliveryMen;
require_once $path . 'check/delivery_men/helper.php';
require_once $path . 'check/delivery_men_login_tokens/helper.php';

use function Check\getDeliveryMenHelper;
use function Check\getDeliveryMenLoginTokensHelper;


function loginDeliveryMan($userId)
{
    $delivery_man = getDeliveryMenHelper()->getData($userId);
    if ($delivery_man == null) {
      $ar = "هذا المستخدم غير مسجل في التوصيل";
      $en = "not register in deliverty";
      exitFromScript($ar, $en);
    }
    return $delivery_man;
}

function getLoginTokenFromUserSessionAndDeliveryManId($userSessionId, $deliveryManId, $loginTokenDuration)
{
    $projectLoginToken = getDeliveryMenLoginTokensHelper()->getData($userSessionId, $deliveryManId);

    if ($projectLoginToken == null) {
        $loginTokenString = uniqid(rand(), false);
        $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
        $projectLoginToken = getDeliveryMenLoginTokensHelper()->addData($userSessionId, $loginTokenString, $deliveryManId, $expireAt);
    } else {
        if (strtotime(getCurruntDate()) > strtotime(getExpireAt($projectLoginToken))) {
            $loginTokenString = uniqid(rand(), false);
            $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
            $projectLoginToken = getDeliveryMenLoginTokensHelper()->updateToken(getId($projectLoginToken), $loginTokenString, $expireAt);
        }
    }
    return $projectLoginToken;
}