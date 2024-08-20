<?php
namespace Check;

require_once (getPath() . 'tables/devices_sessions/attribute.php');

class DevicesSessionsSql extends \DevicesSessionsAttribute
{
    function readSql($deviceId, $appId): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id ,$this->appToken";
        $innerJoin = "";
        $condition = "$this->deviceId = $deviceId And $this->appId = $appId FOR UPDATE";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id ,$this->appToken";
        $innerJoin = "";
        $condition = "$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByDeviceIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = $this->id;
        $innerJoin = "";
        $condition = "$this->deviceId = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function addSql($deviceId, $appId, $appToken): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->deviceId`,`$this->appId`,`$this->appToken`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NULL,$deviceId,$appId,$appToken,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
    protected function updateTokensql($id, $newValue): string
    {
        $table_name = $this->table_name;
        $set_query = "SET $this->appToken = $newValue";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
}
