<?php
namespace Manager;

require_once (getPath() . 'tables/ids_controller/attribute.php');

class IdsControllerSql extends \IdsControllerAttribute
{
    function readSql($tableName): string
    {
        $table_name = $this->table_name;
        $columns = $this->id;
        $innerJoin = "";
        $condition = "$this->tableName = $tableName FOR UPDATE";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function updateIdSql($tableName): string
    {
        $table_name = $this->table_name;
        $set_query = "SET $this->id = $this->id + 1";
        $condition = "$this->tableName = $tableName";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
}
