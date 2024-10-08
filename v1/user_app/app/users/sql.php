<?php
namespace UserApp;

require_once (__DIR__ . '/../../../include/tables/users/attribute.php');

class UsersSql extends \UsersAttribute
{

    function readByIdSql($id): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "$this->name,$this->phone";
        $innerJoin = "";
        $condition = "$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readById2Sql($id): string
    {
        $table_name = $this->table_name;
        $columns = "$this->name,$this->name2,$this->phone";
        $innerJoin = "";
        $condition = "$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    protected function updateName2Sql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->name2 = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }

}
