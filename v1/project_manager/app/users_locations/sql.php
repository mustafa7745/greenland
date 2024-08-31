<?php
namespace Manager;

require_once (getPath() . 'tables/users_locations/attribute.php');

class UsersLocationsSql extends \UsersLocationsAttribute
{
    function addSql($id, $userId, $city, $street, $latLong, $nearTo, $contactPhone, $url): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->userId`,`$this->city`,`$this->street`,`$this->latLong`,`$this->contactPhone`,`$this->nearTo`,`$this->url`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$userId,$city,$street,$latLong,$contactPhone,$nearTo,$url,'$date','$date')";
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
    protected function updateStreetSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->street = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateLatLongSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->latLong = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateNearToSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->nearTo = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateUrlSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->url = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    protected function updateContactPhoneSql($id, $newValue): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->contactPhone = $newValue, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
}
