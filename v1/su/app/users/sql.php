<?php
namespace SU1;

require_once (getPath() . 'tables/users/attribute.php');

class UsersSql extends \UsersAttribute
{

    function readsql($phone): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->phone = $phone";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdsql($id): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id, $this->name, $this->phone, $this->createdAt, $this->updatedAt";
        $innerJoin = "";
        $condition = "$this->id = $id FOR UPDATE";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
