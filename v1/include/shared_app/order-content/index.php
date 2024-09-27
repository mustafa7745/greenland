<?php
require_once 'helper.php';
class OrderContent
{
    public $products;
    public $discount;
    public $delivery;
    public $offers;
    // 
    private $orderProductHelper;
    private $orderDeliveryHelper;
    private $orderDiscountHelper;
    private $orderOfferHelper;

    public function __construct() {
        $this->orderProductHelper = new OrdersProductsHelper();
        $this->orderDeliveryHelper = new OrdersDeliveryHelper();
        $this->orderDiscountHelper = new OrdersDiscountsHelper();
        $this->orderOfferHelper = new OrdersOffersHelper();
    }


    function executeGetData($orderId)
    {
        $products = $this->orderProductHelper->getDataByOrderId($orderId);
        $delivery = $this->orderDeliveryHelper->getDataByOrderId($orderId);
        $discount = $this->orderDiscountHelper->getDataByOrderId($orderId);
        $offers = $this->orderOfferHelper->getDataByOrderId($orderId);
        // 
        $this->products = $products;
        $this->delivery = $delivery;
        $this->offers = $offers;
        $this->discount = $discount;

        // return ['products' => $orderProducts, 'delivery' => $delivery, 'discount' => $discount, 'offers' => $offers];
    }
    function getActualAmount()
    {
        $sum = 0;

        foreach ($this->products as $key => $value) {
            $sum = $sum + ($value['productPrice'] * $value['productQuantity']);
        }
        foreach ($this->offers as $key => $value) {
            $sum = $sum + ($value['offerPrice'] * $value['offerQuantity']);
        }
        if ($this->discount != null) {
            $amount = $this->discount[$this->orderDiscountHelper->amount];
            $type = $this->discount[$this->orderDiscountHelper->type];

            if ($type == $this->orderDiscountHelper->PERCENTAGE_TYPE) {
                $discount = ($sum * $amount) / 100;
                $sum = $sum - (100 * round($discount / 100));
            } else {
                $sum = $sum - $amount;
            }
        }

        $sum = $sum + $this->delivery[$this->orderDeliveryHelper->price];
        $sum = $sum - $this->delivery[$this->orderDeliveryHelper->actualPrice];

        return $sum;
    }
}
