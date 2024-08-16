<?php
namespace DeliveryMen;

require_once (getPath() . 'tables/acceptance/attribute.php');

class AcceptanceSql extends \AcceptanceAttribute
{
    function addSql($deliveryManId, $orderDeliveryId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->deliveryManId`,`$this->orderDeliveryId`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NULL,$deliveryManId,$orderDeliveryId,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
    function readSql($deliveryManId, $status): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->deliveryManId = $deliveryManId AND $this->status = $status";
        /////
        return shared_read_limit2_sql($table_name, $columns, $innerJoin, $this->createdAt, "DESC", $condition, 1);
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
