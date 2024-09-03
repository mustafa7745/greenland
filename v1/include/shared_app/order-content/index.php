<?php
require_once 'helper.php';
class OrderContent
{
    public $orderProducts;
    public $discount;
    public $delivery;
    public $offers;

    function executeGetData($orderId)
    {
        $orderProducts = (new OrdersProductsHelper())->getDataByOrderId($orderId);
        $delivery = (new OrdersDeliveryHelper())->getDataByOrderId($orderId);
        $discount = (new OrdersDiscountsHelper())->getDataByOrderId($orderId);
        $offers = (new OrdersOffersHelper())->getDataByOrderId($orderId);
        // 
        $this->orderProducts = $orderProducts;
        $this->delivery = $delivery;
        $this->offers = $offers;
        $this->discount = $discount;

        // return ['products' => $orderProducts, 'delivery' => $delivery, 'discount' => $discount, 'offers' => $offers];
    }
}
