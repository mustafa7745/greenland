<?php
namespace SU1;

require_once ('sql.php');
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
    $sql = $this->readByIdSql("'$id'");
    $data = shared_execute_read1_no_json_sql($sql);

    if (count($data) != 1) {
      $ar = "_ID_ERROR";
      $en = "_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function getDataByNumber($number)
  {
    $sql = $this->readByNumberSql("'$number'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = $this->name . "_NUMBER_ERROR";
      $en = $this->name . "_NUMBER_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
  function addData($id, $categoryId, $name, $number, $postPrice, $productGroupId)
  {
    $sql = $this->addSql("'$id'", "'$categoryId'", "'$name'", "'$number'", "'$postPrice'", "'$productGroupId'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_Cate";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_Cate" . $sql;
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateName($id, $newValue)
  {
    $sql = $this->updateNameSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateNumber($id, $newValue)
  {
    $sql = $this->updateNumberSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateOrder($id, $newValue)
  {
    $sql = $this->updateOrderSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateGroup($id, $newValue)
  {
    $sql = $this->updateGroupSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updatePostPrice($id, $newValue)
  {
    $sql = $this->updatePostPriceSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updatePrePrice($id, $newValue)
  {
    $sql = $this->updatePrePriceSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }
  function updateAvailable($id)
  {
    $sql = $this->updateAvailableSql("'$id'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_Name";
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

$products_helper = null;
function getProductsHelper()
{
  global $products_helper;
  if ($products_helper == null) {
    $products_helper = new ProductsHelper();
  }
  return $products_helper;
}