<?php
require_once 'helper.php';
class OrderContent
{
    function executeGetData($orderId)
    {
        $orderProducts = (new OrdersProductsHelper())->getDataByOrderId($orderId);
        $delivery = (new OrdersDeliveryHelper())->getDataByOrderId($orderId);
        $discount = (new OrdersDiscountsHelper())->getDataByOrderId($orderId);
        $offers = (new OrdersOffersHelper())->getDataByOrderId($orderId);
        return ['products' => $orderProducts, 'delivery' => $delivery, 'discount' => $discount, 'offers' => $offers];
    }
}
