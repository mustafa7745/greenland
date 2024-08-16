<?php
namespace Check;

require_once (getPath() . 'tables/devices/attribute.php');

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
    function addSql($id, $info, $publicKey): string
    {
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->info`,`$this->publicKey`)";
        $values = "($id,$info,$publicKey)";
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
