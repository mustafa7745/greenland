<?php
namespace SU1;

require_once ('sql.php');
// 
class ProductGroupsHelper extends ProductsGroupsSql
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
  function addData($categoryId, $name, )
  {
    // print_r($name);

    $sql = $this->addSql("'$categoryId'", "'$name'", );
    shared_execute_sql($sql);

    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_Cate";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_Cate" . $sql;
      exitFromScript($ar, $en);
    }
    $id = getDB()->conn->insert_id;
    return $this->getDataById($id);
  }
}

$products_groups_helper = null;
function getProductsGroupsHelper()
{
  global $products_groups_helper;
  if ($products_groups_helper == null) {
    $products_groups_helper = new ProductGroupsHelper();
  }
  return $products_groups_helper;
}