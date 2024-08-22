<?php
namespace UserApp;

require_once (getPath() . 'tables/users/attribute.php');

class UsersSql extends \UsersAttribute
{

    function readByIdSql($id): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "$this->name ,$this->phone";
        $innerJoin = "";
        $condition = "$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }

}
