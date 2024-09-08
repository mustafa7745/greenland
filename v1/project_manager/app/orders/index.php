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
        $loginToken = $this->_check("RUN_APP");
        require_once __DIR__ . '/../delivery_men/executer.php';
        return getOrdersExecuter()->executeAddData(getInputUserId(), getInputOrderProductsIdsWithQnt(), 1, getInputUserLocationId(), getInputDeliveryManId(), $loginToken->managerId);
    }

    function addProductToOrder()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersProductsExecuter()->executeAddData(getInputOrderId(), getInputProductId(), getInputProductQuantity(), $loginToken->managerId);
    }
    function addOfferToOrder()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersOffersExecuter()->executeAddData(getInputOrderId(), getInputOfferId(), getInputOfferQuantity(), $loginToken->managerId);
    }
    function read()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersExecuter()->executeGetData($loginToken->managerId);
    }
    function readOrderDelivery()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersDeliveryExecuter()->executeGetData(getInputOrderId(), $loginToken->managerId);
    }
    function updateQuantity()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersProductsExecuter()->executeUpdateQuantity(getInputOrderProductId(), getInputProductQuantity(), $loginToken->managerId);
    }
    function updateOfferQuantity()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersOffersExecuter()->executeUpdateQuantity(getInputOrderOfferId(), getInputOfferQuantity(), $loginToken->managerId);
    }
    function updateActualPrice()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersDeliveryExecuter()->executeUpdateActualPrice(getInputOrderDeliveryId(), getInputOrderDeliveryActualPrice(), $loginToken->managerId);
    }
    function updatePrice()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersDeliveryExecuter()->executeUpdatePrice(getInputOrderDeliveryId(), getInputOrderDeliveryPrice(), $loginToken->managerId);
    }
    function updateUserLocation()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersDeliveryExecuter()->executeUserLocation(getInputOrderDeliveryId(), getInputUserLocationId(), $loginToken->managerId);
    }
    function cencelOrder()
    {
        $loginToken = $this->_check("RUN_APP");
        require_once __DIR__ . '/../orders_cenceled/helper.php';
        return getOrdersProductsExecuter()->executeCencelOrder(getInputOrderId(), getInputOrderCencelDescription(), $loginToken->managerId);
    }
    function readOrdersOfUsers()
    {
        $this->_check("RUN_APP");
        require_once __DIR__ . '/../users/helper.php';
        return getOrdersProductsExecuter()->executeGetOrdersByUserId(getInputUserId());
    }
    function delete()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersProductsExecuter()->executeDeleteData(getIds(), $loginToken->managerId);
    }
    function deleteOffers()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersOffersExecuter()->executeDeleteData(getIds(), $loginToken->managerId);
    }
    function search()
    {
        $this->_check("RUN_APP");
        return getOrdersExecuter()->executeSearchData(getInputOrderId());
    }
    function updateSystemOrderNumber()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersExecuter()->executeUpdateSystemOrder(getInputOrderId(), getInputOrderSystemNumber(), $loginToken->managerId);
    }
    function readOrderProducts()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersProductsExecuter()->executeGetData(getInputOrderId(), $loginToken->managerId);
    }
    function readOrderCenceled()
    {
        $this->_check("RUN_APP");
        require_once __DIR__ . '/../orders_cenceled/helper.php';
        return getOrdersCenceledHelper()->getDataByOrderId(getInputOrderId());
    }

    function readOrderStatus()
    {
        $this->_check("RUN_APP");
        return getOrdersStatusExecuter()->executeGetData(getInputOrderId());
    }

    ////
    function addDiscount()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersDiscountsExecuter()->executeAddData(getInputOrderId(), getInputOrderDiscountAmount(), getInputOrderDiscountType(), $loginToken->managerId);
    }
    function updateType()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersDiscountsExecuter()->executeUpdateType(getInputOrderId(), getInputOrderDiscountId(), getInputOrderDiscountType(), $loginToken->managerId);
    }
    function updateAmount()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersDiscountsExecuter()->executeUpdateAmount(getInputOrderId(), getInputOrderDiscountId(), getInputOrderDiscountAmount(), $loginToken->managerId);
    }
    function deleteOrderDiscount()
    {
        $loginToken = $this->_check("RUN_APP");
        return getOrdersDiscountsExecuter()->executeDeleteData(getInputOrderId(), getInputOrderDiscountId(), $loginToken->managerId);
    }

    private function _check($permissionName)
    {
        $s = getMainRunApp();
        return getManagerLoginToken($permissionName, $s);
    }


}
