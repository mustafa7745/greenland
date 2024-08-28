<?php 
// namespace DeliveryMen;
require_once $path . 'check/delivery_men/helper.php';

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

