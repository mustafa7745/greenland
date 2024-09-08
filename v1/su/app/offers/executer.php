<?php
namespace SU1;

require_once 'helper.php';
class OffersExecuter
{
  function executeGetData()
  {
    $offers = getOffersHelper()->getData();
    for ($i = 0; $i < count($offers); $i++) {
      $offers[$i]['image'] = $offers[$i]['offer_image_path'] . $offers[$i]['image'];
    }
    return $offers;
  }
  function executeAddData($name, $description, $price, $image, $expireAt)
  {


    $helper = getOffersHelper();
    $full_path_directory = $helper->path_image();
    // 
    createDirectory($full_path_directory);

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $image_name = uniqid(rand(), false) . ".jpg";
    $id = uniqid(rand(), false);
    $expireAt = date('Y-m-d', strtotime(getCurruntDate() . " + $expireAt days"));
    $dataAfterAdd = $helper->addData($id, $name, $description, $image_name, $price, $expireAt);

    // 
    $full_path_file = $full_path_directory . $image_name;
    if (file_put_contents($full_path_file, base64_decode($image)) === false) {
      shared_execute_sql("ROLLBACK");
      FAIL_WHEN_ADD_FILE();
    }
    shared_execute_sql("COMMIT");
    return $dataAfterAdd;
  }

  function executeUpdateName($id, $newValue)
  {
    shared_execute_sql("START TRANSACTION");
    getOffersHelper()->getDataById($id);
    $dataAfterUpdate = getOffersHelper()->updateName($id, $newValue);
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateDescription($id, $newValue)
  {
    shared_execute_sql("START TRANSACTION");
    getOffersHelper()->getDataById($id);
    $dataAfterUpdate = getOffersHelper()->updateDescription($id, $newValue);
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdatePrice($id, $newValue)
  {
    shared_execute_sql("START TRANSACTION");
    getOffersHelper()->getDataById($id);
    $dataAfterUpdate = getOffersHelper()->updatePrice($id, $newValue);
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateEnabled($id)
  {
    shared_execute_sql("START TRANSACTION");
    getOffersHelper()->getDataById($id);
    $dataAfterUpdate = getOffersHelper()->updateEnabled($id);
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }

}

$offers_executer = null;
function getOffersExecuter()
{
  global $offers_executer;
  if ($offers_executer == null) {
    $offers_executer = new OffersExecuter();
  }
  return $offers_executer;
}