<?php 
// namespace DeliveryMen;
require_once $path . 'check/managers/helper.php';
require_once $path . 'check/managers_login_tokens/helper.php';

use function Check\getManagersHelper;
use function Check\getManagersLoginTokensHelper;


function loginManager($userId)
{
    $delivery_man = getManagersHelper()->getData($userId);
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
        $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
        $projectLoginToken = getManagersLoginTokensHelper()->addData($userSessionId, $loginTokenString, $managerId, $expireAt);
    } else {
        if (strtotime(getCurruntDate()) > strtotime(getExpireAt($projectLoginToken))) {
            $loginTokenString = uniqid(rand(), false);
            $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
            $projectLoginToken = getManagersLoginTokensHelper()->updateToken(getId($projectLoginToken), $loginTokenString, $expireAt);
        }
    }
    return $projectLoginToken;
}