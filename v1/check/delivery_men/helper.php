<?php
namespace Check;

require_once ('sql.php');
// 
class DeliveryMenHelper extends DeliveryMenSql
{
  // public $name = "APP";
  function getData($userId)
  {
    $sql = $this->readByUserIdsql("'$userId'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      return null;
    }
    return $data[0];
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
  function addData($id, $userId, $city, $street, $latLong, $nearTo, $contactPhone)
  {

    $sql = $this->addSql("'$id'", "'$userId'", "'$city'", "'$street'", "'$latLong'", "'$nearTo'", "'$contactPhone'");


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

$delivery_men_helper = null;
function getDeliveryMenHelper()
{
  global $delivery_men_helper;
  if ($delivery_men_helper == null) {
    $delivery_men_helper = (new DeliveryMenHelper());
  }
  return $delivery_men_helper;
}