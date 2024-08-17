<?php
namespace Manager;

require_once 'helper.php';
class CollectionsExecuter
{
  function executeGetData($deliveryManId)
  {
    require_once __DIR__ . '/../../app/orders/executer.php';
    $orderExecuter = getOrdersExecuter();
    $collections = getCollectionsHelper()->getData($deliveryManId);
    for ($i = 0; $i < count($collections); $i++) {
      $orderId = $collections[getCollectionsHelper()->orderId];
      $collections[$i]['price'] = $orderExecuter->executeGetFinalOrderPriceWithoutDeliveryPrice($orderId);
    }
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
}

$collections_executer = null;
function getCollectionsExecuter()
{
  global $collections_executer;
  if ($collections_executer == null) {
    $collections_executer = new CollectionsExecuter();
  }
  return $collections_executer;
}