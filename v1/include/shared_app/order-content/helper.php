<?php

require_once 'sql.php';

class OrdersProductsHelper extends OrdersProductsSql
{
    function getDataByOrderId($orderId)
    {
        $sql = $this->readByOrderIdSql("'$orderId'");
        $data = shared_execute_read1_no_json_sql($sql);
        return $data;
    }
}
class OrdersDeliveryHelper extends OrdersDeliverySql
{
    function getDataByOrderId($orderId)
    {
        $sql = $this->readByOrderIdSql("'$orderId'");
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            return null;
        }
        return $data[0];
    }
}
class OrdersDiscountsHelper extends OrdersDiscountsSql
{

    function getDataByOrderId($orderId)
    {
        $sql = $this->readByOrderIdSql("'$orderId'");
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            return null;
        }
        return $data[0];
    }
}
class OrdersOffersHelper extends OrdersOffersSql
{
    function getDataByOrderId($orderId)
    {
        $sql = $this->readByOrderIdSql("'$orderId'");
        $data = shared_execute_read1_no_json_sql($sql);
        return $data;
    }
}