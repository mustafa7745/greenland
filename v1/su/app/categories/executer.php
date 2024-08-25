<?php
namespace SU1;

// require_once "../../../ids_controller/helper.php";
require_once (getSuPath() . 'app/ids_controller/helper.php');
require_once (getSuPath() . 'app/products_groups/helper.php');


require_once ('helper.php');
class CategoriesExecuter
{
  function executeGetData()
  {
    return getCategoriesHelper()->getData();
  }
  function executeAddData($name, $image)
  {
    $categories_helper = getCategoriesHelper();
    // $image = $this->getInputCategoryImage();
    $full_path_directory = $categories_helper->path_image();
    // 
    createDirectory($full_path_directory);
    // 



    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");



    // $category_id = uniqid(rand(), false);
    $id = getId(getIdsControllerHelper()->getData($categories_helper->table_name));

    // getIdsControllerHelper()->updateId($categories_helper->table_name);

    $image_name = uniqid(rand(), false) . ".jpg";

    $productGroupId = getId(getIdsControllerHelper()->getData($categories_helper->table_name));

    getProductsGroupsHelper()->addData($productGroupId, $id, "الرئيسية");
    $dataAfterAdd = $categories_helper->addData($id, $name, $image_name);


    // $dataAfterAdd = $categories_helper->getDataById($id);

    /**
     * ADD INSERTED VALUES TO ProjectINSERtOperations TABLE
     */

    // sharedAddUserInsertOperation($data->getProjectId(), $data->getPermissionId(), $data->getUserSessionId(), $dataAfterAdd);

    // sleep(30);

    $full_path_file = $full_path_directory . $image_name;
    // print_r($full_path_file2);
    if (file_put_contents($full_path_file, base64_decode($image)) === false) {
      shared_execute_sql("ROLLBACK");
      // shared_execute_sql("ROLLBACK");
      FAIL_WHEN_ADD_FILE();
    }

    shared_execute_sql("COMMIT");

    return $dataAfterAdd;


  }
  function executeSearchData($search)
  {
    return getAppsHelper()->searchData($search);
  }
  function executeUpdateName($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    getCategoriesHelper()->updateName($id, $newValue);
    $dataAfterUpdate = getCategoriesHelper()->getDataById($id);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateOrder($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    getCategoriesHelper()->updateOrder($id, $newValue);
    $dataAfterUpdate = getCategoriesHelper()->getDataById($id);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateImage($id, $image)
  {

    $categories_helper = getCategoriesHelper();
    // $image = $this->getInputCategoryImage();
    $full_path_directory = $categories_helper->path_image();
    // 
    createDirectory($full_path_directory);
    // 

    $preImage = $categories_helper->getDataById($id)[$categories_helper->image];


    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    $image_name = uniqid(rand(), false) . ".jpg";

    $categories_helper->updateImage($id, $image_name);
    $dataAfterUpdate = $categories_helper->getDataById($id);

    $full_path_file = $full_path_directory . $image_name;
    // print_r($full_path_file2);
    if (file_put_contents($full_path_file, base64_decode($image)) === false) {
      shared_execute_sql("ROLLBACK");
      // shared_execute_sql("ROLLBACK");
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
  function executeDeleteData($ids)
  {
    $idsString = convertIdsListToStringSql($ids);

    shared_execute_sql("START TRANSACTION");
    require_once __DIR__ . '/../products/helper.php';
    $data = getCategoriesHelper()->getDataById($idsString);
    $innerData = getProductsHelper()->getDataByCategoriesIds($idsString);

    if (count($innerData) != 0) {
      $ar = "قد يكون هناك عناصر موجودة ضمن العناصر المحددة";
      $en = "قد يكون هناك عناصر موجودة ضمن العناصر المحددة";
      exitFromScript($ar, $en);
    }
    getCategoriesHelper()->deleteData($idsString, count($ids));
    shared_execute_sql("COMMIT");
    for ($i = 0; $i < count($data); $i++) {
      unlink(getCategoriesHelper()->path_image() . $data[$i]['image']);
    }
    return successReturn();
  }
}

$categories_executer = null;
function getCategoriesExecuter()
{
  global $categories_executer;
  if ($categories_executer == null) {
    $categories_executer = new CategoriesExecuter();
  }
  return $categories_executer;
}