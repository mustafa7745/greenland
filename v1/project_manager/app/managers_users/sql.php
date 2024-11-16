<?php
namespace Manager;

require_once __DIR__ . '/../../../include/tables/managers_users/attribute.php';

class ManagersUsersSql extends \ManagersUsersAttribute
{
    function addSql($id, $userId, $managerId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,$this->userId,`$this->managerId`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$userId,$managerId,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }

    function readByIdsql($id): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id";
        $innerJoin = "";
        $condition = "$this->id = $id FOR UPDATE";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
