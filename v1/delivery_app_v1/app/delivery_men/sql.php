<?php
namespace DeliveryMen;

require_once __DIR__ . '/../../../include/tables/delivery_men/attribute.php';

class DeliveryMenSql extends \DeliveryMenAttribute
{
    function addSql($id, $userId, $city, $street, $latLong, $nearTo, $contactPhone): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->userId`,`$this->city`,`$this->street`,`$this->latLong`,`$this->contactPhone`,`$this->nearTo`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$userId,$city,$street,$latLong,$contactPhone,$nearTo,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
    function readByUserIdsql($userId): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->userId = $userId";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdsql($id): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->id = $id FOR UPDATE";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
