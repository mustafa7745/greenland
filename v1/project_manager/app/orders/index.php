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

        require_once __DIR__ . '/../delivery_men/executer.php';
        return getOrdersExecuter()->executeAddData(getInputUserId(), getInputOrderProductsIdsWithQnt(), 1, getInputUserLocationId(), getInputDeliveryManId());
    }

    function addProductToOrder()
    {
        // $s = getMainRunApp();
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        // $projectId = getModelMainRunApp()->app->projectId;
        return getOrdersProductsExecuter()->executeAddData(getInputOrderId(), getInputProductId(), getInputProductQuantity());
    }
    function addOfferToOrder()
    {
        // $s = getMainRunApp();
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        // $projectId = getModelMainRunApp()->app->projectId;
        return getOrdersOffersExecuter()->executeAddData(getInputOrderId(), getInputOfferId(), getInputOfferQuantity());
    }
    function read()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersExecuter()->executeGetData();
    }
    function readOrderDelivery()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersDeliveryExecuter()->executeGetData(getInputOrderId());
    }
    function updateQuantity()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersProductsExecuter()->executeUpdateQuantity(getInputOrderProductId(), getInputProductQuantity());
    }
    function updateOfferQuantity()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersOffersExecuter()->executeUpdateQuantity(getInputOrderOfferId(), getInputOfferQuantity());
    }
    function updateActualPrice()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersDeliveryExecuter()->executeUpdateActualPrice(getInputOrderDeliveryId(), getInputOrderDeliveryActualPrice());
    }
    function updatePrice()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersDeliveryExecuter()->executeUpdatePrice(getInputOrderDeliveryId(), getInputOrderDeliveryPrice());
    }
    function cencelOrder()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        require_once __DIR__ . '/../orders_cenceled/helper.php';
        return getOrdersProductsExecuter()->executeCencelOrder(getInputOrderId(), getInputOrderCencelDescription());
    }
    function readOrdersOfUsers()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        require_once __DIR__ . '/../users/helper.php';
        return getOrdersProductsExecuter()->executeGetOrdersByUserId(getInputUserId());
    }
    function delete()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersProductsExecuter()->executeDeleteData(getIds());
    }
    function deleteOffers()
    {
        // $s = getMainRunApp();
        // // print_r(getModelMainRunApp()->app->projectId);
        // $modelUserLoginTokenUserSession = getUserLoginToken("RUN_APP", $s);
        // $userId = $modelUserLoginTokenUserSession->modelUserSession->userId;
        return getOrdersOffersExecuter()->executeDeleteData(getIds());
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

    ////
    function addDiscount()
    {
        return getOrdersDiscountsExecuter()->executeAddData(getInputOrderId(), getInputOrderDiscountAmount(), getInputOrderDiscountType());
    }
    function updateType()
    {
        return getOrdersDiscountsExecuter()->executeUpdateType(getInputOrderDiscountId(), getInputOrderDiscountType());
    }
    function updateAmount()
    {
        return getOrdersDiscountsExecuter()->executeUpdateAmount(getInputOrderDiscountId(), getInputOrderDiscountAmount());
    }
    function deleteOrderDiscount()
    {
        return getOrdersDiscountsExecuter()->executeDeleteData(getInputOrderDiscountId());
    }


}
