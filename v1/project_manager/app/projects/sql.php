<?php
namespace Manager;

require_once (getPath() . 'tables/projects/attribute.php');

class ProjectsSql extends \ProjectsAttribute
{
    function readSql($id): string
    {
        $table_name = $this->table_name;
        $innerJoin = "";
        $columns = "*";
        $condition = "$this->table_name.$this->id = $id";
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
    function readByIdsSql($ids): string
    {
        $table_name = $this->table_name;
        $innerJoin = "";
        $columns = "*";
        $condition = "$this->table_name.$this->id IN ($ids)";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function addSql($id, $categoryId, $name, $number, $postPrice, $productGroupId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->categoryId`,`$this->name`,`$this->number`,`$this->postPrice`,`$this->productGroupId`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$categoryId, $name, $number, $postPrice, $productGroupId ,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
}
