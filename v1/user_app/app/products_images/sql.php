<?php
namespace UserApp;

require_once (getPath() . 'tables/products_images/attribute.php');

class ProductsImagesSql extends \ProductsImagesAttribute
{
    function readByProductIdsSql($ids): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id,$this->image,$this->productId";
        $innerJoin = "";
        $condition = "$this->productId IN ($ids)";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
