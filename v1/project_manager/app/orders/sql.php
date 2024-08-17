<?php
namespace Manager;

require_once (getPath() . 'tables/orders/attribute.php');

class OrdersSql extends \OrdersAttribute
{

    function readSql(): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $columns = "$this->table_name.$this->id,$this->table_name.$this->systemOrderNumber, $this->table_name.$this->createdAt, $this->table_name.$this->situationId, {$this->orders_situations_attribute->table_name}.{$this->orders_situations_attribute->situation}";
        $condition = "$this->situationId <> 1 AND $this->situationId <> 2";
        /////
        return shared_read_order_by_sql($table_name, $columns, $innerJoin, $condition, $this->createdAt, "DESC");
    }
    function searchSql($id): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();

        $columns = "$this->table_name.$this->id,$this->table_name.$this->systemOrderNumber, $this->table_name.$this->createdAt, $this->table_name.$this->situationId, {$this->orders_situations_attribute->table_name}.{$this->orders_situations_attribute->situation}";
        $condition = "$this->table_name.$this->id = $id";
        /////
        return shared_read_order_by_sql($table_name, $columns, $innerJoin, $condition, $this->createdAt, "DESC");
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

    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->id = $id FOR UPDATE";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    protected function updateStatusSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->situationId = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateSystemOrderNumberSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->systemOrderNumber = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
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
    function addSql($orderId, $situationId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->orderId`,`$this->situationId`,`$this->createdAt`)";
        $values = "(NULL, $orderId,$situationId,'$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }

    function readByOrderIdSql($orderId): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $columns = "$this->table_name.$this->id, $this->table_name.$this->orderId, $this->table_name.$this->createdAt,{$this->orders_situations_attribute->table_name}.{$this->orders_situations_attribute->situation}";
        $condition = "$this->orderId = $orderId";
        /////
        return shared_read_order_sql($table_name, $columns, $innerJoin, $condition, "{$this->table_name}" . "." . "{$this->createdAt}", "ASC");
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
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->id = $id FOR UPDATE";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByDeliveryManIdAndIsCollectSql($deliveryManId, $isCollect): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->deliveryManId = $deliveryManId AND $this->isCollect = $isCollect";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
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
    protected function updateDeliveryManIdSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->deliveryManId = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
}

