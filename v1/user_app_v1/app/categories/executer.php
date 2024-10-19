<?php
namespace UserApp;

require_once 'helper.php';
class CategoriesExecuter
{
  function executeGetData()
  {
    return getCategoriesHelper()->getData();
  }
}

$categories_executer = null;
function getCategoriesExecuter()
{
  global $categories_executer;
  if ($categories_executer == null) {
    $categories_executer = new CategoriesExecuter();
  }
  return $categories_executer;
}