<?php
namespace Manager;
require_once __DIR__ . '/../../../include/tables/collections/attribute.php';

class CollectionsSql extends \CollectionsAttribute
{
    function readSql($deliveryManId, $managerId): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->deliveryManId = $deliveryManId AND $this->isCollect = $this->UNCOLLECTED_STATE AND $this->managerId = $managerId";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdsSql($ids, $managerId): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->managerId = $managerId AND $this->id IN ($ids) FOR UPDATE";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    protected function updateIsCollectSql($ids, $managerId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->isCollect = $this->COLLECTED_STATE, $this->updatedAt = '$date'";
        $condition = "$this->managerId = $managerId AND $this->id IN ($ids)";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
}
