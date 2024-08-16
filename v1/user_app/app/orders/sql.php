<?php
namespace UserApp;

require_once (getPath() . 'tables/orders/attribute.php');

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

    function addSql($id, $userId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->userId`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$userId,'$date','$date')";
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

}
/********/
require_once (getPath() . 'tables/orders_products/attribute.php');

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
require_once (getPath() . 'tables/orders_status/attribute.php');

class OrdersStatusSql extends \OrdersStatusAttribute
{

    function readByOrderIdSql($orderId): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $columns = "$this->table_name.$this->id, $this->table_name.$this->orderId, $this->table_name.$this->createdAt,{$this->orders_situations_attribute->table_name}.{$this->orders_situations_attribute->situation}";
        $condition = "$this->orderId = $orderId";
        /////
        return shared_read_order_sql($table_name, $columns, $innerJoin, $condition, "{$this->table_name}" .".". "{$this->createdAt}", "ASC");
    }
}

require_once (getPath() . 'tables/orders_delivery/attribute.php');

class OrdersDeliverySql extends \OrdersDeliveryAttribute
{
    function addSql($orderId, $price, $userLocationId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->price`,`$this->userLocationId`,`$this->orderId`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NULL,$price,$userLocationId, $orderId,'$date','$date')";
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
