<?php
namespace Check;

require_once (getPath() . 'tables/users/attribute.php');

class UsersSql extends \UsersAttribute
{
    function readSql($phone, $password): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id";
        $innerJoin = "";
        $condition = "$this->phone = $phone AND $this->password = SHA2($password,512)";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
