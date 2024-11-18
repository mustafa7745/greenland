<?php
namespace SU1;

require_once('sql.php');
// 
class CouponsHelper extends CouponsSql
{
  function getData()
  {
    $sql = $this->readSql();
    $stmt = getPdo()->prepare($sql);
    // $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $data = $stmt->fetchAll();
    return $data;
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