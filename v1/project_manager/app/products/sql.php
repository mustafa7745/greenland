<?php
namespace Manager;

require_once (getPath() . 'tables/products/attribute.php');

class ProductsSql extends \ProductsAttribute
{
    function readAllSql(): string
    {
        $table_name = $this->table_name;
        $innerJoin = "";
        $columns = "$this->id ,$this->name ,$this->postPrice,$this->number";
        $condition = "1";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $innerJoin = "";
        $columns = "*";
        $condition = "$this->table_name.$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByNumberSql($number): string
    {
        $table_name = $this->table_name;
        $innerJoin = "";
        $columns = "$this->table_name.$this->id ,$this->table_name.$this->name";
        $condition = "$this->table_name.$this->number = $number";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdsSql($ids): string
    {
        $table_name = $this->table_name;
        $innerJoin = "";
        $columns = "*";
        $condition = "$this->table_name.$this->id IN ($ids)";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
