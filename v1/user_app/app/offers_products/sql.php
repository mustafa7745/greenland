<?php
namespace UserApp;

require_once (__DIR__ . '/../../../include/tables/offers_products/attribute.php');

class OffersProductsSql extends \OffersProductsAttribute
{
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $columns = "$this->table_name.$this->id,$this->table_name.$this->productId,$this->table_name.$this->productQuantity ,{$this->products_attribute->table_name}.{$this->products_attribute->name},{$this->products_attribute->table_name}.{$this->products_attribute->postPrice}";
        $condition = "$this->table_name.$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readSql($offerId): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $columns = "$this->table_name.$this->id,$this->table_name.$this->productId,$this->table_name.$this->productQuantity ,{$this->products_attribute->table_name}.{$this->products_attribute->name},{$this->products_attribute->table_name}.{$this->products_attribute->postPrice}";
        $condition = "$this->offerId = $offerId";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }

}
