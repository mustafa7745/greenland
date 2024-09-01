<?php
namespace Manager;

require_once ('sql.php');
// 
class OffersHelper extends OffersSql
{
 
  function getDataByName($name)
  {
    $sql = $this->readByNameSql("'%$name%'");
    // print_r($sql);
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getDataById($id)
  {
    $sql = $this->readByIdSql("'$id'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "ORDER_P_ID_ERROR";
      $en = "ORDER_P_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
}

$offers_helper = null;
function getOffersHelper()
{
  global $offers_helper;
  if ($offers_helper == null) {
    $offers_helper = new OffersHelper();
  }
  return $offers_helper;
}