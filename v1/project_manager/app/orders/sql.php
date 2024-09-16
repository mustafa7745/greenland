<?php
namespace Manager;

require_once (getPath() . 'tables/orders/attribute.php');

class OrdersSql extends \OrdersAttribute
{

    function readNotCompleteSql(): string
    {
        $table_name = $this->table_name;
        $innerJoin = "";
        $columns = "$this->table_name.$this->id";
        $condition = "($this->situationId <> 1 AND $this->situationId <> 2) ";
        /////
        return shared_read_order_by_sql($table_name, $columns, $innerJoin, $condition, $this->createdAt, "DESC");
    }
    // 
    function readSql($orderStatusIds, $managerId): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $columns = "$this->table_name.$this->id,$this->table_name.$this->withApp,$this->table_name.$this->userId,$this->table_name.$this->systemOrderNumber, $this->table_name.$this->createdAt, $this->table_name.$this->situationId, {$this->orders_situations_attribute->table_name}.{$this->orders_situations_attribute->situation}";
        $condition = "($this->situationId NOT IN ($orderStatusIds) AND ($this->table_name.$this->managerId IS NULL OR $this->table_name.$this->managerId = $managerId)) ";
        /////
        return shared_read_order_by_sql($table_name, $columns, $innerJoin, $condition, $this->createdAt, "DESC");
    }
    function readByStatusIdSql($orderStatusId, $managerId): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $columns = "$this->table_name.$this->id,$this->table_name.$this->withApp,$this->table_name.$this->userId,$this->table_name.$this->systemOrderNumber, $this->table_name.$this->createdAt, $this->table_name.$this->situationId, {$this->orders_situations_attribute->table_name}.{$this->orders_situations_attribute->situation}";
        $condition = "($this->situationId = $orderStatusId  AND ($this->table_name.$this->managerId IS NULL OR $this->table_name.$this->managerId = $managerId)) ";
        /////
        return shared_read_order_by_sql($table_name, $columns, $innerJoin, $condition, $this->createdAt, "DESC");
    }
    function searchSql($id): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();

        $columns = "$this->table_name.$this->id,$this->table_name.$this->withApp,$this->table_name.$this->code,$this->table_name.$this->userId,$this->table_name.$this->systemOrderNumber, $this->table_name.$this->createdAt, $this->table_name.$this->situationId, {$this->orders_situations_attribute->table_name}.{$this->orders_situations_attribute->situation}";
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
        $columns = "(`$this->id`,`$this->userId`,`$this->withApp`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$userId,0,'$date','$date')";
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
    function readByUserIdSql($userId): string
    {
        $table_name = $this->table_name;
        // $innerJoin = $this->INNER_JOIN();

        // $columns = "$this->table_name.$this->id,$this->table_name.$this->situationId, $this->table_name.$this->createdAt, {$this->orders_situations_attribute->table_name}.{$this->orders_situations_attribute->situation}";
        $innerJoin = $this->INNER_JOIN();
        $columns = "$this->table_name.$this->id,$this->table_name.$this->systemOrderNumber, $this->table_name.$this->createdAt, $this->table_name.$this->situationId, {$this->orders_situations_attribute->table_name}.{$this->orders_situations_attribute->situation}";


        $condition = "$this->userId = $userId";
        /////
        return shared_read_limit2_sql($table_name, $columns, $innerJoin, "$this->table_name.$this->updatedAt", 'DESC', $condition, 30);
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
    protected function updateManagerIdSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->managerId = $newValue, $this->updatedAt = '$date'";
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
    function addSql($id, $orderId, $productId, $productName, $productPrice, $productQuantity): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->orderId`,`$this->productId`,`$this->productName`,`$this->productPrice`,`$this->productQuantity`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$orderId,$productId,$productName,$productPrice,$productQuantity,'$date','$date')";
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
    function readByOrderId3Sql($orderId): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->orderId = $orderId";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByOrderId2Sql($orderId): string
    {
        $table_name = $this->table_name;
        $columns = "({$this->productPrice} * {$this->productQuantity}) as avg";
        $innerJoin = "";
        $condition = "$this->orderId = $orderId";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByOrderIdAndProductIdSql($orderId, $productId): string
    {
        $table_name = $this->table_name;
        $columns = $this->id;
        $innerJoin = "";
        $condition = "$this->orderId = $orderId AND $this->productId = $productId";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    protected function updateQuantitySql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->productQuantity = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
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
    protected function deleteSql($ids): string
    {
        $table_name = $this->table_name;
        $condition = "$this->id IN ($ids)";
        /////
        return delete_sql($table_name, $condition);
    }

}
/********/
require_once (getPath() . 'tables/orders_offers/attribute.php');

class OrdersOffersSql extends \OrdersOffersAttribute
{
    function addSql($id, $orderId, $offerName, $offerId, $offerQuantity, $offerPrice): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->orderId`,`$this->offerName`,`$this->offerId`,`$this->offerQuantity`,`$this->offerPrice`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$orderId,$offerName,$offerId,$offerQuantity,$offerPrice,'$date','$date')";
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
    function readByOrderIdAndOfferIdSql($orderId, $offerId): string
    {
        $table_name = $this->table_name;
        $columns = $this->id;
        $innerJoin = "";
        $condition = "$this->orderId = $orderId AND $this->offerId = $offerId";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }


    protected function updateQuantitySql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->offerQuantity = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
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
    protected function deleteSql($ids): string
    {
        $table_name = $this->table_name;
        $condition = "$this->id IN ($ids)";
        /////
        return delete_sql($table_name, $condition);
    }

}
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
        $columns = "$this->table_name.$this->id,$this->table_name.$this->situationId, $this->table_name.$this->orderId, $this->table_name.$this->createdAt,{$this->orders_situations_attribute->table_name}.{$this->orders_situations_attribute->situation}";
        $condition = "$this->orderId = $orderId";
        /////
        return shared_read_order_sql($table_name, $columns, $innerJoin, $condition, "{$this->table_name}" . "." . "{$this->createdAt}", "ASC");
    }
}

require_once (getPath() . 'tables/orders_delivery/attribute.php');

class OrdersDeliverySql extends \OrdersDeliveryAttribute
{
    function addSql($id, $orderId, $price, $actualPrice, $userLocationId, $deliveryManId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->price`,`$this->actualPrice`,`$this->userLocationId`,`$this->deliveryManId`,`$this->orderId`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$price,$actualPrice,$userLocationId,$deliveryManId, $orderId,'$date','$date')";
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

    function readByOrderIdsAndDeliveryManIdsSql($orderIds): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id , $this->deliveryManId";
        $innerJoin = "";
        $condition = "$this->orderId IN ($orderIds)";
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
    protected function updateUserLocationIdSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->userLocationId = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateActualPriceSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->actualPrice = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updatePriceSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->price = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
}

require_once (getPath() . 'tables/orders_discounts/attribute.php');

class OrdersDiscountsSql extends \OrdersDiscountsAttribute
{
    function addSql($id, $orderId, $amount, $type): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->orderId`,`$this->amount`,`$this->type`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$orderId,$amount, $type,'$date','$date')";
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
    function readByOrderIdSql($orderId): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->orderId = $orderId";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    protected function updateTypeSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->type = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateAmountSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->amount = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function deleteSql($id): string
    {
        $table_name = $this->table_name;
        $condition = "$this->id = $id";
        /////
        return delete_sql($table_name, $condition);
    }
}


