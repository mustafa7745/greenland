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