<?php
namespace SU1;

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
  function updateQuantity($id, $newValue)
  {
    $sql = $this->updateQuantitySql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_q";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_q";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  
  function addData($id, $offerId, $productId, $productQuantity)
  {
    $sql = $this->addSql("'$id'", "'$offerId'", "'$productId'", "'$productQuantity'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_Cate";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_Cate" . $sql;
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function deleteData($ids, $count)
  {
    $sql = getOffersProductsHelper()->deleteSql($ids);
    // print_r($sql);
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != $count) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_DELETE_";
      $en = "DATA_NOT_EFFECTED_WHEN_DELETE_";
      exitFromScript($ar, $en);
    }
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