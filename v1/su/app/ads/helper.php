<?php
namespace SU1;

require_once 'sql.php';
// 
class AdsHelper extends AdsSql
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
      $ar = "ads" . "_ID_ERROR";
      $en = "" . "_ID_ERROR";
      exitFromScript($ar, $en);
    }
    $data[0]['image'] = $data[0]['ads_image_path'] . $data[0]['image'];
    return $data[0];
  }
  function updateIsEnabled($id)
  {
    $sql = $this->updateIsEnabledSql("'$id'");
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
  function updateImage($id, $newValue)
  {
    $sql = $this->updateImageSql("'$id'", "'$newValue'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_Price";
      $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_Pricce";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);

  }

  function addData($id, $description, $image, $expireAt, $type, $productCatId)
  {
    $sql = $this->addSql("'$id'", "'$description'", "'$image'", $expireAt, $type, $productCatId);
    // print_r($sql);
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
      shared_execute_sql("rollback");
      $ar = "DATA_NOT_EFFECTED_WHEN_ADD_ADS";
      $en = "DATA_NOT_EFFECTED_WHEN_ADD_ADS";
      exitFromScript($ar, $en);
    }
    return $this->getDataById($id);
  }


}

$ads_helper = null;
function getAdsHelper()
{
  global $ads_helper;
  if ($ads_helper == null) {
    $ads_helper = new AdsHelper();
  }
  return $ads_helper;
}