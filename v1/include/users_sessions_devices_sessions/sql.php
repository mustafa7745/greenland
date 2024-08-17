<?php
require_once (getPath() . 'tables/users_sessions/attribute.php');

class UsersSessionsSql extends \UsersSessionsAttribute
{
    function readTokenSql($userId, $appId): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $columns = "{$this->devices_sessions_attribute->appToken}";

        $condition = "{$this->userId} = $userId AND {$this->devices_sessions_attribute->appId} = $appId";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}

// require_once (getPath() . 'tables/devices_sessions/attribute.php');

// class DevicesSessionsSql extends \DevicesSessionsAttribute
// {
//     function readByIdSql($id): string
//     {
//         $table_name = $this->table_name;
//         $columns = $this->id;
//         $innerJoin = "";
//         $condition = "$this->id = $id";
//         return shared_read_sql($table_name, $columns, $innerJoin, $condition);
//     }
// }