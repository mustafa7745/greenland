<?php
namespace SU1;

require_once (getPath() . 'tables/offers_products/attribute.php');

class OffersProductsSql extends \OffersProductsAttribute
{
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readSql($offerId): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $columns = "$this->table_name.$this->id,$this->table_name.$this->productQuantity ,{$this->products_attribute->table_name}.{$this->products_attribute->name},{$this->products_attribute->table_name}.{$this->products_attribute->postPrice}";
        $condition = "$this->offerId = $offerId";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function addSql($id, $offerId, $productId, $productQuantity): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->offerId`,`$this->productId`,`$this->productQuantity`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$offerId,$productId,$productQuantity,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
    protected function updateQuantitySql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->productQuantity = $newValue, $this->updatedAt = '$date'";
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
