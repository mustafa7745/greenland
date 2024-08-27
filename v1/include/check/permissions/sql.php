<?php

require_once (getPath() . 'tables/permissions/attribute.php');

class PermissionsSql extends \PermissionsAttribute
{
    function readByNameSql($name): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->name = $name";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
