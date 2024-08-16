<?php
namespace UserApp;

require_once 'sql.php';
// 
class ProductsHelper extends ProductsSql
{
  function getData($categoryId)
  {
    $sql = $this->readSql("'$categoryId'");
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
  function getDataByIds($ids)
  {
    $sql = $this->readByIdsSql($ids);
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function addData($id, $categoryId, $name, $number, $postPrice, $productGroupId)
  {
    $sql = $this->addSql("'$id'", "'$categoryId'", "'$name'", "'$number'", "'$postPrice'", "'$productGroupId'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_Cate";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_Cate";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
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