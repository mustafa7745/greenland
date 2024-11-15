<?php
namespace UserApp;

require_once 'helper.php';
class CouponsExecuter
{
  function executeGetDataByCode($code, $runApp)
  {
    $failedCount = getFailedAttempsLogsHelper()->getData($runApp->device->id, 7);
    // print_r($failedCount);
    if (getDeviceCount($failedCount) > 4) {
      $this->_BLOCKED();
    }
    if (getIpCount($failedCount) > 4) {
      $this->_BLOCKED();
    }

    $data = getCouponsHelper()->getDataByCode($code, $runApp, 7);
    return $data;
  }
  function _BLOCKED()
  {
    $ar = "لايمكنك التحقق مرة اخرى بسبب المحاولات الكثيرة الفاشلة";
    $en = "لايمكنك التحقق مرة اخرى بسبب المحاولات الكثيرة الفاشلة";

    exitFromScript($ar, $en);
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

