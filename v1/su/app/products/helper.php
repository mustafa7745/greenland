<?php
namespace SU1;

require_once ('sql.php');
// 
class ProductsHelper extends ProductsSql
{
  // public $name = "APP";
  function getData($categoryId)
  {
    $sql = $this->readSql("'$categoryId'");
    // $ar = "DATA_NOT_EFFECTED_WHEN_ADD_Cate" . $sql;
    // $en = "DATA_NOT_EFFECTED_WHEN_ADD_Cate" . $sql;
    // exitFromScript($ar, $en);
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getDataById($id)
  {
    $sql = $this->readByIdSql($id);

    $data = shared_execute_read1_no_json_sql($sql);

    if (count($data) != 1) {
      $ar = $this->name . "_ID_ERROR" . json_encode($data);
      $en = $this->name . "_ID_ERROR" . json_encode(getInputProductGroupIdWithNull());
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function addData($id, $categoryId, $name, $number, $postPrice, $productGroupId)
  {
  
    $sql = $this->addSql("'$id'", "'$categoryId'", "'$name'", "'$number'", "'$postPrice'", "'$productGroupId'");


    shared_execute_sql($sql);

    // $ar = "DATA_NOT_EFFECTED_WHEN_ADD_Cate" . mysqli_affected_rows(getDB()->conn) . $id;
    // $en = "DATA_NOT_EFFECTED_WHEN_ADD_Cate" . mysqli_affected_rows(getDB()->conn) . $sql;
    // exitFromScript($ar, $en);

    // print_r($id);

    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_Cate";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_Cate" . $sql;
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }

  function searchData($search)
  {
    $sql = $this->search_sql($search);
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }


  // function getDataById($id)
  // {
  //   $sql = $this->read_by_id_sql("'$id'");
  //   $data = shared_execute_read_no_json_sql($sql)->data;
  //   if (count($data) != 1) {
  //     $ar = $this->name . "_ID_ERROR";
  //     $en = $this->name . "_ID_ERROR";
  //     exitFromScript($ar, $en);
  //   }
  //   return $data[0];
  // }

  function updateName($id, $name)
  {

    $sql = $this->update_name_sql("'$name'", "'$id'");

    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      exitFromScript($ar, $en);
    }
  }
  function updateSha($id, $name)
  {

    $sql = $this->update_sha_sql("'$name'", "'$id'");

    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_SHA";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_SHA";
      exitFromScript($ar, $en);
    }
  }
  function updateVersion($id, $name)
  {

    $sql = $this->update_version_sql("'$name'", "'$id'");

    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_SHA";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_SHA";
      exitFromScript($ar, $en);
    }
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