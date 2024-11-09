<?php
namespace SU1;

require_once 'helper.php';
class ProductsExecuter
{
  function executeGetData()
  {
    require_once __DIR__ . '/../../app/categories/helper.php';

    return getProductsHelper()->getData(getInputCategoryId());
  }
  function executeGetDataByNumber($number)
  {
    $date = getProductsHelper()->getDataByNumber($number);
    return $this->sharedData1($date);
  }
  function executeAddData($categoryId, $name, $number, $postPrice, $image, $productGroupId)
  {
    $helper = getProductsHelper();
    require_once __DIR__ . '/../../app/products_images/helper.php';
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

    $image_name = uniqid(rand(), false) . ".jpg";

    $product = $helper->addData($categoryId, $name, $number, $postPrice, $productGroupId);
    $productId = $product[$helper->id];
    $productImage = $productImageshelper->addData($productId, $image_name);


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

  function executeAddWithoutImage($categoryId, $name, $number, $postPrice, $productGroupId)
  {
    $helper = getProductsHelper();

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");


    $product = $helper->addData($categoryId, $name, $number, $postPrice, $productGroupId);


    shared_execute_sql("COMMIT");

    return $product;
  }

  function executeUpdateName($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    ;
    getProductsHelper()->getDataById($id);
    $dataAfterUpdate = getProductsHelper()->updateName($id, $newValue);

    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateDescription($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    ;
    getProductsHelper()->getDataById($id);
    $dataAfterUpdate = getProductsHelper()->updateDescription($id, $newValue);

    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $this->sharedData1(data: $dataAfterUpdate);
    ;
  }
  function executeUpdateCategory($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    ;
    getProductsHelper()->getDataById($id);
    $dataAfterUpdate = getProductsHelper()->updateCategory($id, $newValue);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $this->sharedData1(data: $dataAfterUpdate);
  }
  function executeUpdateNumber($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    ;
    getProductsHelper()->getDataById($id);
    $dataAfterUpdate = getProductsHelper()->updateNumber($id, $newValue);
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
    ;
    getProductsHelper()->getDataById($id);
    $dataAfterUpdate = getProductsHelper()->updateOrder($id, $newValue);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateGroup($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    ;
    getProductsHelper()->getDataById($id);
    $dataAfterUpdate = getProductsHelper()->updateGroup($id, $newValue);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdatePostPrice($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $data = getProductsHelper()->getDataById($id);
    $prePrice = $data[getProductsHelper()->postPrice];
    getProductsHelper()->updatePrePrice($id, $prePrice);
    $dataAfterUpdate = getProductsHelper()->updatePostPrice($id, $newValue);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateAvailable($id)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    ;
    getProductsHelper()->getDataById($id);
    $dataAfterUpdate = getProductsHelper()->updateAvailable($id);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeDeleteData($ids)
  {
    $idsString = convertIdsListToStringSql($ids);

    shared_execute_sql("START TRANSACTION");
    require_once __DIR__ . '/../products_images/helper.php';
    $images = getProductsImagesHelper()->getDataByProductsIds($idsString);

    if (count($images) != 0) {
      $ar = "قد يكون هناك صور موجودة ضمن العناصر المحددة";
      $en = "قد يكون هناك صور موجودة ضمن العناصر المحددة";
      exitFromScript($ar, $en);
    }
    getProductsHelper()->deleteData($idsString, count($ids));
    shared_execute_sql("COMMIT");
    return successReturn();
  }

  function sharedData1($data)
  {
    require_once __DIR__ . '/../../app/categories/helper.php';
    $categoryId = $data[getProductsHelper()->categoryId];
    $category = getCategoriesHelper()->getDataById($categoryId);
    $data['category'] = $category;
    return $data;
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