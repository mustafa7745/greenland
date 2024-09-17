<?php
require_once (__DIR__ . '/../tables/projects/attribute.php');

class ProjectsSql extends \ProjectsAttribute 
{ 
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id,$this->serviceAccountKey,$this->latLong,$this->priceDeliveryPer1km";
        $innerJoin = "";
        $condition = "$this->id = $id FOR UPDATE";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
