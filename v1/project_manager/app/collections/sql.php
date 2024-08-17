<?php
namespace Manager;

require_once (getPath() . 'tables/collections/attribute.php');

class CollectionsSql extends \CollectionsAttribute
{
    function readSql($deliveryManId): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = $this->deliveryManId = $deliveryManId;
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
