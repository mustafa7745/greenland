<?php

require_once __DIR__ . '/../../tables/orders_products/attribute.php';

class OrdersProductsSql extends \OrdersProductsAttribute
{

    function readByOrderIdSql($orderId): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->orderId = $orderId";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }

}

require_once __DIR__ . '/../../tables/orders_offers/attribute.php';


class OrdersOffersSql extends \OrdersOffersAttribute
{

    function readByOrderIdSql($orderId): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->orderId = $orderId";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }

}

require_once __DIR__ . '/../../tables/orders_delivery/attribute.php';

class OrdersDeliverySql extends \OrdersDeliveryAttribute
{
    function readByOrderIdSql($orderId): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->orderId = $orderId";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}

require_once __DIR__ . '/../../tables/orders_discounts/attribute.php';


class OrdersDiscountsSql extends \OrdersDiscountsAttribute
{

    function readByOrderIdSql($orderId): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->orderId = $orderId";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }

}


