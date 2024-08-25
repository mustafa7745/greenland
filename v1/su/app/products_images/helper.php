<?php
namespace SU1;

require_once ('sql.php');
// 
class ProductsImagesHelper extends ProductsImagesSql
{
  // public $name = "APP";
  function getData($productId)
  {
    $sql = $this->readSql("'$productId'");
    // print_r($sql);
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getDataById($id)
  {
    $sql = $this->readByIdSql("'$id'");

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

    if (count($data) != 1) {
      $ar = $this->name . "_ID_ERROR";
      $en = $this->name . "_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getDataByProductsIds($ids)
  {
    $sql = $this->readByProductsIdsSql($ids);
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function addData($id, $productId, $image)
  {
    // print_r($name);

    $sql = $this->addSql("'$id'", "'$productId'", "'$image'");
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
    $sql = $this->deleteSql($ids);
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

$products_images_helper = null;
function getProductsImagesHelper()
{
  global $products_images_helper;
  if ($products_images_helper == null) {
    $products_images_helper = new ProductsImagesHelper();
  }
  return $products_images_helper;
}