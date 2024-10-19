<?php
namespace UserApp;

require_once ('sql.php');
// 
class CategoriesHelper extends CategoriesSql
{
  function getData()
  {
    $sql = $this->readSql();
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
}

$categories_helper = null;
function getCategoriesHelper()
{
  global $categories_helper;
  if ($categories_helper == null) {
    $categories_helper = new CategoriesHelper();
  }
  return $categories_helper;
}