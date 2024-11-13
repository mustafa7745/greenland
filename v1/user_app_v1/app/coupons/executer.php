<?php
namespace UserApp;

require_once 'helper.php';
class CouponsExecuter
{
  function executeGetDataByCode($code)
  {
    $data = getCouponsHelper()->getDataByCode(id: $code);
    return $data;
  }
}
$coupons_executer = null;
function getCouponsExecuter()
{
  global $coupons_executer;
  if ($coupons_executer == null) {
    $coupons_executer = new CouponsExecuter();
  }
  return $coupons_executer;
}

