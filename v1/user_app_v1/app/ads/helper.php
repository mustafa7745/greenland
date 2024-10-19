<?php
namespace UserApp;

require_once 'sql.php';
// 
class AdsHelper extends AdsSql
{
  function getData()
  {
    $sql = $this->readSql();
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
}

$ads_helper = null;
function getAdsHelper()
{
  global $ads_helper;
  if ($ads_helper == null) {
    $ads_helper = new AdsHelper();
  }
  return $ads_helper;
}