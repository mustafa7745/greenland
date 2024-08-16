<?php
namespace SU1;

require_once (getPath() . 'tables/categories/attribute.php');

class CategoriesSql extends \CategoriesAttribute
{
    function readSql(): string
    {
        $table_name = $this->table_name;
        $columns = getColumnImagePath(" * ", "category_image_path");
        $innerJoin = "";
        $condition = "1";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = getColumnImagePath(" * ", "category_image_path");

        $innerJoin = "";
        $condition = "$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function addSql($id, $name, $image): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->name`,`$this->image`)";
        $values = "($id,$name,$image)";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
}
