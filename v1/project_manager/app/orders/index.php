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
        $this->_check("RUN_APP");
        require_once __DIR__ . '/../delivery_men/executer.php';
        return getOrdersExecuter()->executeAddData(getInputUserId(), getInputOrderProductsIdsWithQnt(), 1, getInputUserLocationId(), getInputDeliveryManId());
    }

    function addProductToOrder()
    {
        $this->_check("RUN_APP");
        return getOrdersProductsExecuter()->executeAddData(getInputOrderId(), getInputProductId(), getInputProductQuantity());
    }
    function addOfferToOrder()
    {
        $this->_check("RUN_APP");
        return getOrdersOffersExecuter()->executeAddData(getInputOrderId(), getInputOfferId(), getInputOfferQuantity());
    }
    function read()
    {
        $this->_check("RUN_APP");
        return getOrdersExecuter()->executeGetData();
    }
    function readOrderDelivery()
    {
        $this->_check("RUN_APP");
        return getOrdersDeliveryExecuter()->executeGetData(getInputOrderId());
    }
    function updateQuantity()
    {
        $this->_check("RUN_APP");
        return getOrdersProductsExecuter()->executeUpdateQuantity(getInputOrderProductId(), getInputProductQuantity());
    }
    function updateOfferQuantity()
    {
        $this->_check("RUN_APP");
        return getOrdersOffersExecuter()->executeUpdateQuantity(getInputOrderOfferId(), getInputOfferQuantity());
    }
    function updateActualPrice()
    {
        $this->_check("RUN_APP");
        return getOrdersDeliveryExecuter()->executeUpdateActualPrice(getInputOrderDeliveryId(), getInputOrderDeliveryActualPrice());
    }
    function updatePrice()
    {
        $this->_check("RUN_APP");
        return getOrdersDeliveryExecuter()->executeUpdatePrice(getInputOrderDeliveryId(), getInputOrderDeliveryPrice());
    }
    function cencelOrder()
    {
        $this->_check("RUN_APP");
        require_once __DIR__ . '/../orders_cenceled/helper.php';
        return getOrdersProductsExecuter()->executeCencelOrder(getInputOrderId(), getInputOrderCencelDescription());
    }
    function readOrdersOfUsers()
    {
        $this->_check("RUN_APP");
        require_once __DIR__ . '/../users/helper.php';
        return getOrdersProductsExecuter()->executeGetOrdersByUserId(getInputUserId());
    }
    function delete()
    {
        $this->_check("RUN_APP");
        return getOrdersProductsExecuter()->executeDeleteData(getIds());
    }
    function deleteOffers()
    {
        $this->_check("RUN_APP");
        return getOrdersOffersExecuter()->executeDeleteData(getIds());
    }
    function search()
    {
        $this->_check("RUN_APP");
        return getOrdersExecuter()->executeSearchData(getInputOrderId());
    }
    function updateSystemOrderNumber()
    {
        $this->_check("RUN_APP");
        return getOrdersExecuter()->executeUpdateSystemOrder(getInputOrderId(), getInputOrderSystemNumber());
    }
    function readOrderProducts()
    {
        $this->_check("RUN_APP");
        return getOrdersProductsExecuter()->executeGetData(getInputOrderId());
    }
    function readOrderCenceled()
    {
        $this->_check("RUN_APP");
        require_once __DIR__ . '/../orders_cenceled/helper.php';
        return getOrdersCenceledHelper()->getDataByOrderId(getInputOrderId());
    }
    function readUncollectedOrders()
    {
        $this->_check("RUN_APP");
        return getOrdersDeliveryExecuter()->executeGetUncollectedOrders(getInputDeliveryManId());
    }
    function readOrderStatus()
    {
        $this->_check("RUN_APP");
        return getOrdersStatusExecuter()->executeGetData(getInputOrderId());
    }

    ////
    function addDiscount()
    {
        $this->_check("RUN_APP");
        return getOrdersDiscountsExecuter()->executeAddData(getInputOrderId(), getInputOrderDiscountAmount(), getInputOrderDiscountType());
    }
    function updateType()
    {
        $this->_check("RUN_APP");
        return getOrdersDiscountsExecuter()->executeUpdateType(getInputOrderId(), getInputOrderDiscountId(), getInputOrderDiscountType());
    }
    function updateAmount()
    {
        $this->_check("RUN_APP");
        return getOrdersDiscountsExecuter()->executeUpdateAmount(getInputOrderId(), getInputOrderDiscountId(), getInputOrderDiscountAmount());
    }
    function deleteOrderDiscount()
    {
        $this->_check("RUN_APP");
        return getOrdersDiscountsExecuter()->executeDeleteData(getInputOrderDiscountId());
    }

    private function _check($permissionName)
    {
        $s = getMainRunApp();
        getManagerLoginToken($permissionName, $s);
    }


}
