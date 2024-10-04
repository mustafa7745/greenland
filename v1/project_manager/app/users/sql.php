<?php
namespace Manager;

require_once (getPath() . 'tables/users/attribute.php');

class UsersSql extends \UsersAttribute
{
    function addSql($id, $phone, $name, $password): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,$this->phone,`$this->name2`,`$this->password`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$phone,$name,SHA2($password,512),'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
    function readsql($phone): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id, $this->name, $this->name2, $this->phone,$this->createdAt";
        $innerJoin = "";
        $condition = "$this->phone = $phone";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdsql($id): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id, $this->name,$this->name2, $this->phone, $this->createdAt, $this->updatedAt";
        $innerJoin = "";
        $condition = "$this->id = $id FOR UPDATE";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    protected function updateNameSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->name2 = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updatePasswordSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->password = SHA2($newValue,512), $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
}
