<?php
namespace Manager;

require_once (getPath() . 'tables/categories/attribute.php');

// require_once "../../../ids_controller/helper.php";
require_once (getSuPath() . 'app/ids_controller/helper.php');
require_once (getSuPath() . 'app/products_images/helper.php');


require_once ('helper.php');
class ProductsExecuter
{
  function executeGetData()
  {
    $data = getProductsHelper()->getData(getInputCategoryId());
    for ($i = 0; $i < count($data); $i++) {
      $data[$i]["productImages"] = [];
    }


    return $data;
  }
  function executeGetAllData()
  {
    $data = getProductsHelper()->getAllData();
    return $data;
  }
  function executeGetDataByNumber($number)
  {
    return getProductsHelper()->getDataByNumber($number);
  }
  function executeAddData($categoryId, $name, $number, $postPrice, $image, $productGroupId)
  {
    $helper = getProductsHelper();
    $productImageshelper = getProductsImagesHelper();

    // $image = $this->getInputCategoryImage();
    $full_path_directory = $helper->path_image();
    // 
    createDirectory($full_path_directory);
    // 



    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");



    // $category_id = uniqid(rand(), false);
    $productId = getId(getIdsControllerHelper()->getData($helper->table_name));
    $productImageId = getId(getIdsControllerHelper()->getData($productImageshelper->table_name));


    $image_name = uniqid(rand(), false) . ".jpg";

    $product = $helper->addData($productId, $categoryId, $name, $number, $postPrice, $productGroupId);
    $productImage = $productImageshelper->addData($productImageId, $productId, $image_name);


    // $dataAfterAdd = $helper->getDataById($id);

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

    return $product;
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

    $apps_helper = getAppsHelper();
    $app = $apps_helper->getDataById($id);
    $updated_id = getId($app);
    $preValue = getPackageName($app);
    $apps_helper->updateName($id, $newValue);
    $dataAfterUpdate = $apps_helper->getDataById($id);
    /**
     * ADD Updated VALUE TO UserUpdatedOperations TABLE
     */
    // sharedAddUserUpdateOperation($data->getUserId(), $data->getPermissionId(), $data->getUserSessionId(), $updated_id, $preValue, $newValue);

    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateSha($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    $apps_helper = getAppsHelper();
    $app = $apps_helper->getDataById($id);
    $updated_id = getId($app);
    $preValue = getPackageName($app);
    $apps_helper->updateSha($id, $newValue);
    $dataAfterUpdate = $apps_helper->getDataById($id);
    /**
     * ADD Updated VALUE TO UserUpdatedOperations TABLE
     */
    // sharedAddUserUpdateOperation($data->getUserId(), $data->getPermissionId(), $data->getUserSessionId(), $updated_id, $preValue, $newValue);

    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateVersion($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    $apps_helper = getAppsHelper();
    $app = $apps_helper->getDataById($id);
    $updated_id = getId($app);
    $preValue = getPackageName($app);
    $apps_helper->updateVersion($id, $newValue);
    $dataAfterUpdate = $apps_helper->getDataById($id);
    /**
     * ADD Updated VALUE TO UserUpdatedOperations TABLE
     */
    // sharedAddUserUpdateOperation($data->getUserId(), $data->getPermissionId(), $data->getUserSessionId(), $updated_id, $preValue, $newValue);

    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
}

$products_executer = null;
function getProductsExecuter()
{
  global $products_executer;
  if ($products_executer == null) {
    $products_executer = new ProductsExecuter();
  }
  return $products_executer;
}