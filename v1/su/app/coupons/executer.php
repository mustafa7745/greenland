<?php
namespace SU1;

require_once 'helper.php';
class CouponsExecuter
{
  function executeGetData()
  {
    $data = getCouponsHelper()->getData();
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

