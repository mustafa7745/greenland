<?php
namespace SU1;

require_once 'helper.php';
class ProductsGroupsExecuter
{
  function executeGetData()
  {
    return getProductsGroupsHelper()->getData(getInputCategoryId());
  }
  function executeAddData($categoryId, $name)
  {
    $helper = getProductsGroupsHelper();

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $dataAfterAdd = $helper->addData($categoryId, $name);
    shared_execute_sql("COMMIT");

    return $dataAfterAdd;
  }
}

$products_groups_executer = null;
function getProductsGroupsExecuter()
{
  global $products_groups_executer;
  if ($products_groups_executer == null) {
    $products_groups_executer = new ProductsGroupsExecuter();
  }
  return $products_groups_executer;
}