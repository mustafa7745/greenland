<?php
namespace Manager;

// To Get RunApp
require_once "../../../include/check/index.php";
// To Get Token
require_once "../../../include/token/index.php";
// To Get Executer
require_once 'executer.php';
class Orders
{

    function add()
    {
        $s = getMainRunApp();
        $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        $projectId = getModelMainRunApp()->app->projectId;
        return getOrdersExecuter()->executeAddData($userId, getInputOrderProductsIdsWithQnt(), $projectId, getInputUserLocationId());
    }
    function read()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersExecuter()->executeGetData();
    }
    function search()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersExecuter()->executeSearchData(getInputOrderId());
    }
    function updateSystemOrderNumber()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersExecuter()->executeUpdateSystemOrder(getInputOrderId(), getInputOrderSystemNumber());
    }
    function readOrderProducts()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersProductsExecuter()->executeGetData(getInputOrderId());
    }
    function readUncollectedOrders()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersDeliveryExecuter()->executeGetUncollectedOrders(getInputDeliveryManId());
    }
    function readOrderStatus()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersStatusExecuter()->executeGetData(getInputOrderId());
    }
    // function readOrderProducts($order_id)
    // {
    //     $resultData = $this->check->check("READ_CATEGORIES");
    //     return getOrdersProductsExecuter()->getData($order_id, $resultData->getProjectIdFromApp());
    // }
    // function readOrderStatus($order_id)
    // {
    //     $resultData = $this->check->check("READ_CATEGORIES");
    //     return getOrdersStatusExecuter()->getData($order_id);
    // }
}
