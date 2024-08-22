<?php
namespace SU1;

require_once (getPath() . 'tables/ads/attribute.php');

class AdsSql extends \AdsAttribute
{
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readSql(): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "1";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function addSql($id, $description, $image): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->image`,`$this->description`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$image,$description,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
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
    protected function updateIsEnabledSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->isEnabled = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateDescriptionSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->description = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
}
