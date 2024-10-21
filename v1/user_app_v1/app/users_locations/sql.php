<?php
namespace UserApp;

require_once (__DIR__ . '/../../../include/tables/users_locations/attribute.php');

class UsersLocationsSql extends \UsersLocationsAttribute
{
    function addSql($userId, $city, $street, $latLong, $nearTo, $contactPhone): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $type = "NULL";
        $locationType = getInputLocationTypeId();
        if ($locationType != null) {
            $type = $locationType;
        }
        $columns = "(`$this->id`,`$this->userId`,`$this->city`,`$this->street`,`$this->latLong`,`$this->contactPhone`,`$this->nearTo`,`$this->type`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NULL,$userId,$city,$street,$latLong,$contactPhone,$nearTo,$type,'$date','$date')";
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
        return shared_read_order_by_sql($table_name, $columns, $innerJoin, $condition, $this->createdAt, "DESC");
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