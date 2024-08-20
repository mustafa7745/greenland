<?php
namespace DeliveryMen;

require_once ('helper.php');
class ReservationsExecuter
{
  function executeGetData($deliveryManId)
  {

    $data = getReservationsHelper()->getData($deliveryManId);
    if (count($data) == 1) {
      return ["success" => "true"];
    }
    if (count($data) > 1) {
      $ar = "طلبات حجز كثيرة كثيرة";
      $en = "طلبات حجز كثيرة كثيرة";
      exitFromScript($ar, $en);
    }

    require_once (getDeliveryPath() . 'app/acceptance/helper.php');

    $acceptance = getAcceptanceHelper()->getData($deliveryManId, getAcceptanceHelper()->WAIT_TO_ACCEPT_STATUS);
    if ($acceptance != null) {
      require_once (getDeliveryPath() . 'app/orders/helper.php');
      $orderDelivery = getOrdersDeliveryHelper()->getDataById(getOrderDeliveryId($acceptance));
      $order = getOrdersHelper()->getDataById($orderDelivery[getOrdersDeliveryHelper()->orderId]);
      // if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
      //   return ["success" => "false"];
      // }
      $ordersProducts = getOrdersProductsHelper()->getOrderProductsByOrderWithItsStuff1(getId($order));
      $ordersProducts["acceptStatus"] = getAcceptanceHelper()->WAIT_TO_ACCEPT_STATUS;
      $ordersProducts["systemOrderNumber"] = $order[getOrdersHelper()->systemOrderNumber];
      $ordersProducts["code"] = $order[getOrdersHelper()->code];
      return $ordersProducts;
      // $orderProducts = getOrdersProductsHelper()->getOrderProductsByOrderId($orderDelivery[getOrdersDeliveryHelper()->orderId]);
      // $orderDelivery["products"] = $orderProducts;
      // return $orderDelivery;
    }
    // 
    $acceptance = getAcceptanceHelper()->getData($deliveryManId, getAcceptanceHelper()->ACCEPTED_STATUS);

    if ($acceptance != null) {

      require_once (getDeliveryPath() . 'app/orders/helper.php');

      $orderDelivery = getOrdersDeliveryHelper()->getDataById(getOrderDeliveryId($acceptance));
      $order = getOrdersHelper()->getDataById($orderDelivery[getOrdersDeliveryHelper()->orderId]);
      if ($order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_COMPLETED || $order[getOrdersHelper()->situationId] == getOrdersHelper()->ORDER_CENCELED) {
        return ["success" => "false"];
      }
      // print_r($order);
      $situatinId = $order[getOrdersHelper()->situationId];
      if ($situatinId != getOrdersHelper()->ORDER_COMPLETED and $situatinId != getOrdersHelper()->ORDER_CENCELED) {
        $ordersProducts = getOrdersProductsHelper()->getOrderProductsByOrderWithItsStuff1(getId($order));
        $ordersProducts["acceptStatus"] = getAcceptanceHelper()->ACCEPTED_STATUS;
        $ordersProducts["systemOrderNumber"] = $order[getOrdersHelper()->systemOrderNumber];
        $ordersProducts["code"] = $order[getOrdersHelper()->code];
        return $ordersProducts;
        // return;
      }
      return ["success" => "false"];
    }
    return ["success" => "false"];
    // return $data;
  }
  function executeAddData($deliveryManId)
  {
    $helper = getReservationsHelper();
    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");

    if (count($helper->getData($deliveryManId)) > 0) {
      $ar = "تم الحجز مسبقا";
      $en = "Reserved";
      exitFromScript($ar, $en);
    }
    ;

    // $category_id = uniqid(rand(), false);
    // $id = getId(getIdsControllerHelper()->getData($helper->table_name));
    $dataAfterAdd = $helper->addData($deliveryManId);
    // $dataAfterAdd = $helper->getDataById($id);

    // print_r($dataAfterAdd);
    /**
     * ADD INSERTED VALUES TO ProjectINSERtOperations TABLE
     */

    shared_execute_sql("COMMIT");
    return ["success" => "true"];
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
$reservations_executer = null;
function getReservationsExecuter()
{
  global $reservations_executer;
  if ($reservations_executer == null) {
    $reservations_executer = (new ReservationsExecuter());
  }
  return $reservations_executer;
}

