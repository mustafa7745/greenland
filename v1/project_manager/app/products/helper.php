<?php
namespace Manager;

require_once ('sql.php');
// 
class ProductsHelper extends ProductsSql
{
  function getAllData()
  {
    $sql = $this->readAllSql();
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getDataById($id)
  {
    $sql = $this->readByIdSql($id);

    $data = shared_execute_read1_no_json_sql($sql);

    if (count($data) != 1) {
      $ar = $this->name . "_ID_ERROR";
      $en = $this->name . "_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getDataByNumber($number)
  {
    $sql = $this->readByNumberSql($number);

    $data = shared_execute_read1_no_json_sql($sql);

    if (count($data) != 1) {
      $ar = $this->name . "_NUMBER_ERROR";
      $en = $this->name . "_NUMBER_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getDataByIds($ids)
  {
    $sql = $this->readByIdsSql($ids);
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
}

$products_helper = null;
function getProductsHelper()
{
  global $products_helper;
  if ($products_helper == null) {
    $products_helper = new ProductsHelper();
  }
  return $products_helper;
}