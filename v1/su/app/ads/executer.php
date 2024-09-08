<?php
namespace SU1;

require_once 'helper.php';
class AdsExecuter
{
  function executeGetData()
  {
    $ads = getAdsHelper()->getData();
    for ($i = 0; $i < count($ads); $i++) {
      $ads[$i]['image'] = $ads[$i]['ads_image_path'] . $ads[$i]['image'];
    }
    return $ads;
  }
  function executeAddData($description, $image)
  {
    $helper = getAdsHelper();
    $full_path_directory = $helper->path_image();
    // 
    createDirectory($full_path_directory);

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $image_name = uniqid(rand(), false) . ".jpg";
    $id = uniqid(rand(), false);
    $dataAfterAdd = $helper->addData($id, $description, $image_name);

    // 
    $full_path_file = $full_path_directory . $image_name;
    if (file_put_contents($full_path_file, base64_decode($image)) === false) {
      shared_execute_sql("ROLLBACK");
      FAIL_WHEN_ADD_FILE();
    }
    shared_execute_sql("COMMIT");
    return $dataAfterAdd;
  }
  function executeUpdateImage($id, $newValue)
  {
    $helper = getAdsHelper();
    $full_path_directory = $helper->path_image();
    // 
    shared_execute_sql("START TRANSACTION");
    $ads = getAdsHelper()->getDataById($id);
    $preImage = $ads[$helper->image];
    $image_name = uniqid(rand(), false) . ".jpg";
    $dataAfterUpdate = $helper->updateImage($id, $image_name);
    // 
    $full_path_file = $full_path_directory . $image_name;
    if (file_put_contents($full_path_file, base64_decode($newValue)) === false) {
      shared_execute_sql("ROLLBACK");
      FAIL_WHEN_ADD_FILE();
    }
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    $full_path_file = $full_path_directory . $preImage;
    unlink($full_path_file);
    return $dataAfterUpdate;
  }
  function executeUpdateDescription($id, $newValue)
  {
    shared_execute_sql("START TRANSACTION");
    getAdsHelper()->getDataById($id);
    $dataAfterUpdate = getAdsHelper()->updateDescription($id, $newValue);
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateIsEnabled($id)
  {
    shared_execute_sql("START TRANSACTION");
    getAdsHelper()->getDataById($id);
    $dataAfterUpdate = getAdsHelper()->updateIsEnabled($id);
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }

}

$ads_executer = null;
function getAdsExecuter()
{
  global $ads_executer;
  if ($ads_executer == null) {
    $ads_executer = new AdsExecuter();
  }
  return $ads_executer;
}