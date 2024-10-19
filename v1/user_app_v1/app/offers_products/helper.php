<?php
namespace UserApp;

require_once 'sql.php';
// 
class OffersProductsHelper extends OffersProductsSql
{
  function getData($offerId)
  {
    $sql = $this->readSql("'$offerId'");
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getDataById($id)
  {
    $sql = $this->readByIdSql("'$id'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "_ID_ERROR";
      $en = "_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
 
}

$offers_products_helper = null;
function getOffersProductsHelper()
{
  global $offers_products_helper;
  if ($offers_products_helper == null) {
    $offers_products_helper = new OffersProductsHelper();
  }
  return $offers_products_helper;
}