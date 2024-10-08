<?php
namespace SU1;

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




    $image_name = uniqid(rand(), false) . ".jpg";

    $dataAfterAdd = $helper->addData($productId, $image_name);


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
  function executeDeleteData($ids)
  {
    $idsString = convertIdsListToStringSql($ids);

    shared_execute_sql("START TRANSACTION");
    require_once __DIR__ . '/../products_images/helper.php';
    $images = getProductsImagesHelper()->getDataByIds($idsString);
    getProductsImagesHelper()->deleteData($idsString, count($ids));
    shared_execute_sql("COMMIT");
    for ($i = 0; $i < count($images); $i++) {
      $image = getProductsHelper()->path_image() . $images[$i]['image'];
      unlink($image);
    }
    return successReturn();
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