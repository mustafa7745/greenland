<?php
namespace UserApp;

require_once (__DIR__ . '/../../../include/tables/products/attribute.php');

class ProductsSql extends \ProductsAttribute
{
    function readSql($categoryId_P): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        // 
        $id = "$this->table_name . $this->id";
        $prePrice = "$this->table_name . $this->prePrice";
        $postPrice = "$this->table_name . $this->postPrice";
        $categoryId = "$this->table_name . $this->categoryId";
        $createdAt = "$this->table_name . $this->createdAt";
        $updatedAt = "$this->table_name . $this->updatedAt";
        $productGroupId = "{$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->id} as '{$this->products_groups_attribute->table_name}Id'";
        $productGroupName = "{$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->name} as '{$this->products_groups_attribute->table_name}Name'";
        $name = "$this->table_name . $this->name";
        $isAvailable = "$this->table_name . $this->isAvailable";

        $columns = "$id,$prePrice,$postPrice,$categoryId,$isAvailable,$createdAt, $updatedAt,$name,$productGroupName,$productGroupId";
        $condition = "$this->table_name.$this->categoryId = $categoryId_P";
        return shared_read_order_by_sql($table_name, $columns, $innerJoin, $condition, "`$this->order`,$this->table_name.$this->updatedAt", "ASC");
    }
    function searchSql($productName): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        // 
        $id = "$this->table_name . $this->id";
        $prePrice = "$this->table_name . $this->prePrice";
        $postPrice = "$this->table_name . $this->postPrice";
        $categoryId = "$this->table_name . $this->categoryId";
        $createdAt = "$this->table_name . $this->createdAt";
        $updatedAt = "$this->table_name . $this->updatedAt";
        $productGroupId = "{$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->id} as '{$this->products_groups_attribute->table_name}Id'";
        $productGroupName = "{$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->name} as '{$this->products_groups_attribute->table_name}Name'";
        $name = "$this->table_name . $this->name";
        $isAvailable = "$this->table_name . $this->isAvailable";

        $columns = "$id,$prePrice,$postPrice,$categoryId,$isAvailable,$createdAt, $updatedAt,$name,$productGroupName,$productGroupId";
        $condition = "$this->table_name.$this->name LIKE '%:productName%'";

        return shared_read_limit2_sql($table_name, $columns, $innerJoin, "$this->table_name.$this->order", "ASC", $condition, 7);
    }
    function readDiscountsSql(): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        // 
        $id = "$this->table_name . $this->id";
        $prePrice = "$this->table_name . $this->prePrice";
        $postPrice = "$this->table_name . $this->postPrice";
        $categoryId = "$this->table_name . $this->categoryId";
        $createdAt = "$this->table_name . $this->createdAt";
        $updatedAt = "$this->table_name . $this->updatedAt";
        $productGroupId = "{$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->id} as '{$this->products_groups_attribute->table_name}Id'";
        $productGroupName = "{$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->name} as '{$this->products_groups_attribute->table_name}Name'";
        $name = "$this->table_name . $this->name";
        $isAvailable = "$this->table_name . $this->isAvailable";

        $columns = "$id,$prePrice,$postPrice,$categoryId,$isAvailable,$createdAt, $updatedAt,$name,$productGroupName,$productGroupId";
        $condition = "$this->table_name.$this->postPrice < $this->table_name.$this->prePrice";
        return shared_read_order_by_sql($table_name, $columns, $innerJoin, $condition, "$this->table_name . $this->updatedAt", "DESC");
    }
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();

        $columns = "$this->table_name.$this->id ,$this->table_name.$this->name, {$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->id} as '{$this->products_groups_attribute->table_name}Id' , {$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->name} as '{$this->products_groups_attribute->table_name}Name'";

        $condition = "$this->table_name.$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdsSql($ids): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        // 
        $id = "$this->table_name . $this->id";
        $prePrice = "$this->table_name . $this->prePrice";
        $postPrice = "$this->table_name . $this->postPrice";
        $categoryId = "$this->table_name . $this->categoryId";
        $createdAt = "$this->table_name . $this->createdAt";
        $updatedAt = "$this->table_name . $this->updatedAt";
        $productGroupId = "{$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->id} as '{$this->products_groups_attribute->table_name}Id'";
        $productGroupName = "{$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->name} as '{$this->products_groups_attribute->table_name}Name'";
        $name = "$this->table_name . $this->name";
        $isAvailable = "$this->table_name . $this->isAvailable";

        $columns = "$id,$prePrice,$postPrice,$categoryId,$isAvailable,$createdAt, $updatedAt,$name,$productGroupName,$productGroupId";

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
