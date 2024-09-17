<?php

require_once (__DIR__ . '/../../tables/permissions_groups/attribute.php');

class PermissionsGroupsSql extends \PermissionsGroupsAttribute
{
    function readSql($permissionId, $groupId): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id";
        $innerJoin = "";
        $condition = "$this->permissionId = $permissionId AND $this->groupId = $groupId";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
