<?php
namespace UserApp;

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
        $projectId = getMainRunApp()->app->projectId;
        require_once __DIR__ . '/../../../include/tables/orders_offers/attribute.php';
        return getOrdersExecuter()->executeAddData($userId, getInputOrderProductsIdsWithQnt(), getInputOrderOffersIdsWithQnt(), $projectId, getInputUserLocationId());
    }
    function read()
    {
        $s = getMainRunApp();
        // print_r(getModelMainRunApp()->app->projectId);
        $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersExecuter()->executeGetData($userId, getFrom());
    }
    function readOrderProducts()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersProductsExecuter()->executeGetData(getInputOrderId());
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
