<?php
namespace UserApp;

require_once(__DIR__ . '/../../../include/tables/orders/attribute.php');

class OrdersSql extends \OrdersAttribute
{

    function readSql($userId, $offset): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = " $this->userId = $userId";
        /////
        return shared_read_limit_sql($table_name, $columns, $innerJoin, " {$this->updatedAt}", "DESC", $offset, $condition);
    }

    function readSituationSql($userId): string
    {
        $table_name = $this->table_name;
        $columns = $this->situationId;
        $innerJoin = "";
        // $condition = "($this->order_order_situation_id <> 1 OR $this->order_order_situation_id <> 2) AND $this->order_project_id = $project_id AND $this->order_userId = $userId";
        $condition = "($this->situationId <> 1 AND $this->situationId <> 2)  AND $this->userId = $userId";

        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }

    function addSql($userId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->userId`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NULL,$userId,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }

    function read_by_id_sql($id): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->order_id = $id FOR UPDATE";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function read_by_id_and_userId_sql($userId, $orderId): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->id = $orderId AND $this->userId = $userId";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }

}
/********/
require_once(__DIR__ . '/../../../include/tables/orders_products/attribute.php');

class OrdersProductsSql extends \OrdersProductsAttribute
{
    function addSql($orderId, $productId, $productName, $productPrice, $productQuantity): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->orderId`,`$this->productId`,`$this->productName`,`$this->productPrice`,`$this->productQuantity`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NULL,$orderId,$productId,$productName,$productPrice,$productQuantity,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }

    function readByOrderIdSql($orderId): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id,$this->productId,$this->productName,$this->productPrice,$this->productQuantity,$this->createdAt,$this->updatedAt,({$this->productPrice} * {$this->productQuantity}) as avg";
        $innerJoin = "";
        $condition = "$this->orderId = $orderId";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }

}
/********/

/********/
require_once(__DIR__ . '/../../../include/tables/orders_status/attribute.php');

class OrdersStatusSql extends \OrdersStatusAttribute
{

    function readByOrderIdSql($orderId): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $situation = "{$this->orders_situations_attribute->table_name}.{$this->orders_situations_attribute->situation}";
        $situationId = "{$this->orders_situations_attribute->table_name}.{$this->orders_situations_attribute->id} as situationId";
        $columns = "$this->table_name.$this->id, $this->table_name.$this->orderId, $this->table_name.$this->createdAt,$situation,$situationId";
        $condition = "$this->orderId = $orderId";
        /////
        return shared_read_order_sql($table_name, $columns, $innerJoin, $condition, "{$this->table_name}" . "." . "{$this->createdAt}", "ASC");
    }
}

require_once(__DIR__ . '/../../../include/tables/orders_delivery/attribute.php');

class OrdersDeliverySql extends \OrdersDeliveryAttribute
{
    function addSql($orderId, $price, $actualPrice, $userLocationId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->price`,$this->actualPrice,`$this->userLocationId`,`$this->orderId`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NULL,$price,$actualPrice,$userLocationId, $orderId,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
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
/********/
require_once __DIR__ . '/../../../include/tables/orders_offers/attribute.php';

class OrdersOffersSql extends \OrdersOffersAttribute
{
    function addSql($orderId, $offerId, $offerName, $offerPrice, $offerQuantity): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->orderId`,`$this->offerId`,`$this->offerName`,`$this->offerPrice`,`$this->offerQuantity`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NULL,$orderId,$offerId,$offerName,$offerPrice,$offerQuantity,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
}

require_once(getPath() . 'tables/orders_discounts/attribute.php');

class OrdersDiscountsSql extends \OrdersDiscountsAttribute
{
    function addSql($orderId, $amount, $type, $couponId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->orderId`,`$this->amount`,`$this->type`,`$this->couponId`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NULL,$orderId,$amount, $type,$couponId,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
}


