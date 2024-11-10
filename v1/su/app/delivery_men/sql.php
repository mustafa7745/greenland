<?php
namespace SU1;

require_once __DIR__ . '/../../../include/tables/delivery_men/attribute.php';


class DeliveryMenSql extends \DeliveryMenAttribute
{
    function searchSql($userId): string
    {
        $table_name = $this->table_name;
        $innerJoin = "";
        $columns = "*";
        $condition = "$this->userId = $userId";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }

    function addSql($id, $userId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "($this->id, $this->userId, $this->createdAt)";
        $values = "($id, $userId, '$date')";
        return shared_insert_sql($table_name, $columns, $values);
    }

    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->id = $id";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    protected function updateStatusSql($id): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->status = NOT $this->status";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
}
