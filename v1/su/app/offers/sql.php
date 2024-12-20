<?php
namespace SU1;

require_once __DIR__ . '/../../../include/tables/offers/attribute.php';
class OffersSql extends \OffersAttribute
{
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = getColumnImagePath(" * ", "offer_image_path");
        $innerJoin = "";
        $condition = "$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readSql(): string
    {
        $table_name = $this->table_name;
        $columns = getColumnImagePath(" * ", "offer_image_path");
        $innerJoin = "";
        $condition = "1";
        return shared_read_limit2_sql($table_name, $columns, $innerJoin, "{$this->createdAt}", "DESC", $condition, 5);

    }
    function addSql($id, $name, $description, $image, $price, $expireAt): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->name`,`$this->description`,`$this->image`,`$this->price`,`$this->expireAt`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$name,$description,$price,$image,$expireAt,'$date','$date')";
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
    protected function updateEnabledSql($id): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->isEnabled = NOT $this->isEnabled, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updatePriceSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->price = $newValue, $this->updatedAt = '$date'";
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
    protected function updateImageSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->image = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
}
