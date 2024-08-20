<?php
namespace UserApp;

require_once (getPath() . 'tables/products_images/attribute.php');

class ProductsImagesSql extends \ProductsImagesAttribute
{
    function readByProductIdsSql($ids): string
    {
        $table_name = $this->table_name;
        $columns = getColumnImagePath("$this->id,$this->image,$this->productId,$this->createdAt", "product_image_path");
        $innerJoin = "";
        $condition = "$this->productId IN ($ids)";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
