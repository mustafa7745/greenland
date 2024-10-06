<?php
namespace UserApp;

require_once (__DIR__ . '/../../../include/tables/location_types/attribute.php');

class LocationTypesSql extends \LocationTypesAttribute
{
    function readSql(): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "1";
        /////
        return shared_read_order_by_sql($table_name, $columns, $innerJoin, $condition, "`$this->order`", "DESC");
    }
}
