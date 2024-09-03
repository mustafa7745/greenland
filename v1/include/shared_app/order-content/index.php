<?php
require_once 'helper.php';
class OrderContent
{
    public $products;
    public $discount;
    public $delivery;
    public $offers;

    function executeGetData($orderId)
    {
        $products = (new OrdersProductsHelper())->getDataByOrderId($orderId);
        $delivery = (new OrdersDeliveryHelper())->getDataByOrderId($orderId);
        $discount = (new OrdersDiscountsHelper())->getDataByOrderId($orderId);
        $offers = (new OrdersOffersHelper())->getDataByOrderId($orderId);
        // 
        $this->products = $products;
        $this->delivery = $delivery;
        $this->offers = $offers;
        $this->discount = $discount;

        // return ['products' => $orderProducts, 'delivery' => $delivery, 'discount' => $discount, 'offers' => $offers];
    }
}
