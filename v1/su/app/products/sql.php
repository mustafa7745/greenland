<?php
namespace SU1;

require_once (getPath() . 'tables/products/attribute.php');

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
        $number = "$this->table_name . $this->number";
        $order = "$this->table_name . `$this->order`";


        $createdAt = "$this->table_name . $this->createdAt";
        $updatedAt = "$this->table_name . $this->updatedAt";
        $productGroupId = "{$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->id} as '{$this->products_groups_attribute->table_name}Id'";
        $productGroupName = "{$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->name} as '{$this->products_groups_attribute->table_name}Name'";
        $name = "$this->table_name . $this->name";

        $columns = "$id,$prePrice,$postPrice,$categoryId, $number,$order,$createdAt, $updatedAt,$name,$productGroupName,$productGroupId";
        $condition = "$this->table_name.$this->categoryId = $categoryId_P";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();

        $id = "$this->table_name . $this->id";
        $prePrice = "$this->table_name . $this->prePrice";
        $postPrice = "$this->table_name . $this->postPrice";
        $categoryId = "$this->table_name . $this->categoryId";
        $number = "$this->table_name . $this->number";
        $order = "$this->table_name . `$this->order`";


        $createdAt = "$this->table_name . $this->createdAt";
        $updatedAt = "$this->table_name . $this->updatedAt";
        $productGroupId = "{$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->id} as '{$this->products_groups_attribute->table_name}Id'";
        $productGroupName = "{$this->products_groups_attribute->table_name}.{$this->products_groups_attribute->name} as '{$this->products_groups_attribute->table_name}Name'";
        $name = "$this->table_name . $this->name";
        $columns = "$id,$prePrice,$postPrice,$categoryId, $number,$order,$createdAt, $updatedAt,$name,$productGroupName,$productGroupId";
        $condition = "$this->table_name.$this->id = $id FOR UPDATE";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByNumberSql($number): string
    {
        $table_name = $this->table_name;
        $innerJoin = "";
        $columns = "$this->table_name.$this->id ,$this->table_name.$this->name";
        $condition = "$this->table_name.$this->number = $number";
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
    protected function updateNameSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->name = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateNumberSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->number = $newValue, $this->updatedAt = '$date'";
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
    protected function updatePostPriceSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->postPrice = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updatePrePriceSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->prePrice = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateGroupSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->productGroupId = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateAvailableSql($id): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->isAvailable = NOT $this->isAvailable, $this->updatedAt = '$date'";
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
