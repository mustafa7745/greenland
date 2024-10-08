<?php
namespace Manager;

require_once ('helper.php');
class ProductsExecuter
{
  function executeGetAllData()
  {
    $data = getProductsHelper()->getAllData();
    return $data;
  }
  function executeGetDataByNumber($number)
  {
    return getProductsHelper()->getDataByNumber($number);
  }
}

$products_executer = null;
function getProductsExecuter()
{
  global $products_executer;
  if ($products_executer == null) {
    $products_executer = new ProductsExecuter();
  }
  return $products_executer;
}