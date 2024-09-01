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