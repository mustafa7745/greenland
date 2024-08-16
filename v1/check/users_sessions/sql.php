<?php
namespace Check;

require_once (getPath() . 'tables/users_sessions/attribute.php');

class UsersSessionSql extends \UsersSessionsAttribute
{
    function readSql($userId, $deviceSessionId): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->userId = $userId AND $this->deviceSessionId = $deviceSessionId";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function addSql($userId, $deviceSessionId): string
    {
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->userId`,`$this->deviceSessionId`)";
        $values = "(Null,$userId,$deviceSessionId)";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
}
