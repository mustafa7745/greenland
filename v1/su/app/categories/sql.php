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
        return shared_read_order_by_sql($table_name, $columns, $innerJoin, $condition, "`$this->order`", "ASC");
    }
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = getColumnImagePath(" * ", "category_image_path");

        $innerJoin = "";
        $condition = "$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdsSql($ids): string
    {
        $table_name = $this->table_name;
        $columns = $this->image;
        $innerJoin = "";
        $condition = "$this->id IN ($ids) FOR UPDATE";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function addSql($name, $image): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->name`,`$this->image`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NULL,$name,$image,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
    protected function updateNameSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->name = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateOrderSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET `$this->order` = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateImageSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->image = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function deleteSql($ids): string
    {
        $table_name = $this->table_name;
        $condition = "$this->id IN ($ids)";
        /////
        return delete_sql($table_name, $condition);
    }
}
