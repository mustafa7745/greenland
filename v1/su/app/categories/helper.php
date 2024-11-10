<?php
namespace SU1;

require_once('sql.php');
// 
class CategoriesHelper extends CategoriesSql
{
  // public $name = "APP";
  function getData()
  {
    $sql = $this->readSql();
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
  function getSearch($value)
  {
    $sql = $this->searchSql($value);
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = $this->name . "_NAME_ERROR";
      $en = $this->name . "_NAME_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
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
    return $data;
  }
  function addData($name, $image)
  {
    // print_r($name);

    $sql = $this->addSql("'$name'", "'$image'");
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
  function updateOrder($id, $name)
  {
    $sql = $this->updateOrderSql("'$id'", "'$name'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);

  }
  function updateImage($id, $name)
  {
    $sql = $this->updateImageSql("'$id'", "'$name'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);

  }
  function updateName($id, $name)
  {
    $sql = $this->updateNameSql("'$id'", "'$name'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_NAME";
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

$categories_helper = null;
function getCategoriesHelper()
{
  global $categories_helper;
  if ($categories_helper == null) {
    $categories_helper = new CategoriesHelper();
  }
  return $categories_helper;
}