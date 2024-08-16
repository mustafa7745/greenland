<?php
namespace SU1;

require_once (getPath() . 'tables/products_images/attribute.php');

class ProductsImagesSql extends \ProductsImagesAttribute
{
    function readSql($productId): string
    {
        $table_name = $this->table_name;
        $columns = getColumnImagePath(" * ", "product_image_path");
        $innerJoin = "";
        $condition = "$this->productId = $productId";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = getColumnImagePath(" * ", "product_image_path");
        $innerJoin = "";
        $condition = "$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function addSql($id, $productId, $image): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->productId`,`$this->image`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id, $productId, $image, '$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
}
