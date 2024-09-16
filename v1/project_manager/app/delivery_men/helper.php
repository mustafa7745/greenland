<?php
namespace Manager;

require_once 'sql.php';
// 
class DeliveryMenHelper extends DeliveryMenSql
{
  // public $name = "APP";
  function getData($userId)
  {
    $sql = $this->readByUserIdsql("'$userId'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      return null;
    }
    return $data[0];
  }
  function getData2()
  {
    $sql = $this->readSql();
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getDataByUserPhone($phone)
  {
    $sql = $this->readByUserPhonesql("'$phone'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "المستخدم غير موجود";
      $en = "المستخدم غير موجود";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getDataById($id)
  {
    $sql = $this->readByIdSql("'$id'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = $this->name . "_ID_ERROR";
      $en = $this->name . "_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getDataById2($id)
  {
    $sql = $this->readById2Sql("'$id'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = $this->name . "_ID_ERROR";
      $en = $this->name . "_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }

}

$delivery_men_helper = null;
function getDeliveryMenHelper()
{
  global $delivery_men_helper;
  if ($delivery_men_helper == null) {
    $delivery_men_helper = (new DeliveryMenHelper());
  }
  return $delivery_men_helper;
}