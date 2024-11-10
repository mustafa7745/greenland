<?php
namespace Check;

require_once(__DIR__ . '/../../tables/users/attribute.php');

class UsersSql extends \UsersAttribute
{
    function readSql($phone, $password): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id,$this->status";
        $innerJoin = "";
        $condition = "$this->phone = $phone AND $this->password = SHA2($password,512)";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id,$this->status";
        $innerJoin = "";
        $condition = "$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
