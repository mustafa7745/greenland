<?php
namespace SU1;

require_once (getPath() . 'tables/products/attribute.php');

class ProductsSql extends \ProductsAttribute
{
    function readSql($categoryId): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();

        $columns = "$this->table_name.$this->id ,$this->table_name.$this->name, {$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->id} as '{$this->products_groups_attribute->table_name}Id' , {$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->name} as '{$this->products_groups_attribute->table_name}Name'";
        $condition = "$this->table_name.$this->categoryId = $categoryId";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();

        $columns = "$this->table_name.$this->id ,$this->table_name.$this->name, {$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->id} as '{$this->products_groups_attribute->table_name}Id' , {$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->name} as '{$this->products_groups_attribute->table_name}Name'";
        
        $condition = "$this->table_name.$this->id = $id";
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
