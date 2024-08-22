<?php
namespace UserApp;

require_once 'sql.php';
// 
class OffersHelper extends OffersSql
{
  function getData()
  {
    $sql = $this->readSql();
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