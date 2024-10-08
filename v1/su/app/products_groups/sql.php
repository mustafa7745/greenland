<?php
namespace SU1;

require_once (getPath() . 'tables/products_groups/attribute.php');

class ProductsGroupsSql extends \ProductsGroupsAttribute
{
    function readSql($categoryId): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->categoryId = $categoryId";
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
    function addSql($categoryId, $name): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->categoryId`,`$this->name`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NULL,$categoryId, $name,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
}
