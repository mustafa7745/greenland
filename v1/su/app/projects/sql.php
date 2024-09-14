<?php
namespace SU1;

require_once (getPath() . 'tables/projects/attribute.php');

class ProjectsSql extends \ProjectsAttribute
{
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $innerJoin = "";
        $columns = "$this->id, $this->priceDeliveryPer1km, $this->latLong , $this->deviceId , $this->requestOrderMessage ,$this->requestOrderStatus ";
        $condition = "$this->id  = $id FOR UPDATE";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    protected function updatePasswordSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->password = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateDeviceIdSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->deviceId = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateLatLongSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->latLong = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateRequestOrderStatusSql($id): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->requestOrderStatus = NOT $this->requestOrderStatus, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateRequestOrderMessageSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->requestOrderMessage =  $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updatePriceDeliveryPer1kmSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET `$this->priceDeliveryPer1km` = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
}
