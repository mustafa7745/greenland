<?php
namespace Manager;

require_once (getPath() . 'tables/acceptance/attribute.php');

class AcceptanceSql extends \AcceptanceAttribute
{
    function addSql($id, $deliveryManId, $orderDeliveryId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->deliveryManId`,`$this->orderDeliveryId`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$deliveryManId,$orderDeliveryId,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
    function readSql($orderDeliveryId): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->orderDeliveryId = $orderDeliveryId AND ($this->status = $this->WAIT_TO_ACCEPT_STATUS OR $this->status = $this->ACCEPTED_STATUS)";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByOrderDeliveryIdAndStatusSql($orderDeliveryId, $status): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->orderDeliveryId = $orderDeliveryId AND $this->status = $status";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdsql($id): string
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
        $set_query = "SET $this->status = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
}
