<?php
namespace SU1;

// require_once "../../../products/helper.php";
require_once (getSuPath() . 'app/ids_controller/helper.php');
require_once (getSuPath() . 'app/products/helper.php');


require_once ('helper.php');
class ProductsImagesExecuter
{
  function executeGetData()
  {
    return getProductsImagesHelper()->getData(getInputProductId());
  }
  function executeAddData($productId, $image)
  {
    $helper = getProductsImagesHelper();
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
    $id = getId(getIdsControllerHelper()->getData($helper->table_name));

    getIdsControllerHelper()->updateId($helper->table_name);

    $image_name = uniqid(rand(), false) . ".jpg";

    $dataAfterAdd = $helper->addData($id, $productId, $image_name);


    // $dataAfterAdd = $helper->getDataById($id);

    /**
     * ADD INSERTED VALUES TO ProjectINSERtOperations TABLE
     */

    // sharedAddUserInsertOperation($data->getProjectId(), $data->getPermissionId(), $data->getUserSessionId(), $dataAfterAdd);

    // sleep(30);s

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

$products_images_executer = null;
function getProductsImagesExecuter()
{
  global $products_images_executer;
  if ($products_images_executer == null) {
    $products_images_executer = new ProductsImagesExecuter();
  }
  return $products_images_executer;
}