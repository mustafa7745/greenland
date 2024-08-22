<?php
namespace SU1;

require_once 'sql.php';
// 
class OffersHelper extends OffersSql
{
  function getData()
  {
    $sql = $this->readSql();
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
  function updateDescription($id, $newValue)
  {
    $sql = $this->updateDescriptionSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_Description";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_Description";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);

  }
  function updatePrice($id, $newValue)
  {
    $sql = $this->updateNameSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_Price";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_Pricce";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);

  }

  function addData($id, $name, $description, $price, $image, $expireAt)
  {
    $sql = $this->addSql("'$id'", "'$name'", "'$description'", "'$image'", "'$price'", "'$expireAt'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_Cate";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_Cate" . $sql;
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }


}

$offers_helper = null;
function getOffersHelper()
{
  global $offers_helper;
  if ($offers_helper == null) {
    $offers_helper = new OffersHelper();
  }
  return $offers_helper;
}