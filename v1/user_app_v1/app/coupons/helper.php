<?php
namespace UserApp;

require_once ('sql.php');
// 
class CouponsHelper extends CouponsSql
{
  function getDataByCode($id)
  {
    $sql = $this->readByCodesql($id);
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "كود غير صحيح";
      $en =  "ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
}

$coupons_helper = null;
function getCouponsHelper()
{
  global $coupons_helper;
  if ($coupons_helper == null) {
    $coupons_helper = (new CouponsHelper());
  }
  return $coupons_helper;
}