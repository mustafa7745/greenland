<?php
namespace Manager;

require_once (getPath() . 'tables/delivery_men/attribute.php');

class DeliveryMenSql extends \DeliveryMenAttribute
{
    function readByUserIdsql($userId): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->userId = $userId";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByUserPhonesql($phone): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $columns = "{$this->table_name}.$this->id , {$this->users_attribute->table_name}.{$this->users_attribute->name}";

        $condition = "{$this->users_attribute->table_name}.{$this->users_attribute->phone} = $phone";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdsql($id): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->id = $id FOR UPDATE";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readById2sql($id): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $columns = "{$this->table_name}.$this->id , {$this->users_attribute->table_name}.{$this->users_attribute->name}, {$this->users_attribute->table_name}.{$this->users_attribute->phone}";
        $condition = "$this->id = $id";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
