<?php
namespace UserApp;

// To Get RunApp
require_once "../../../include/check/index_v1.php";
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
        $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersExecuter()->executeGetData($userId, getFrom());
    }
    function readOrderContent()
    {
        $s = getMainRunApp();
        $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersExecuter()->executeGetOrderContentWithDelivery($userId, getInputOrderId());
    }
    function readOrderProducts()
    {
        return getOrdersProductsExecuter()->executeGetData(getInputOrderId());
    }
    function readOrderStatus()
    {
        return getOrdersStatusExecuter()->executeGetData(getInputOrderId());
    }
}
