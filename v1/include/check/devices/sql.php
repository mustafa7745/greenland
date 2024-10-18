<?php
namespace Check;

require_once(__DIR__ . '/../../tables/devices/attribute.php');

class DevicesSql extends \DevicesAttribute
{
    function readSql($id): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id ,$this->publicKey";
        $innerJoin = "";
        $condition = "$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function addSql($id, $info): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->info`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$info,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
    protected function updatePublicKeyql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->publicKey = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
}
