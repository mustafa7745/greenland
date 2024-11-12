<?php
namespace Manager;

require_once (getPath() . 'tables/users_locations/attribute.php');

class UsersLocationsSql extends \UsersLocationsAttribute
{
    function addSql($userId, $city, $street, $latLong, $nearTo, $contactPhone): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->userId`,`$this->city`,`$this->street`,`$this->latLong`,`$this->contactPhone`,`$this->nearTo`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NULL,$userId,$city,$street,$latLong,$contactPhone,$nearTo,'$date','$date')";
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
        $innerJoin = $this->INNER_JOIN();
        $columns = "{$this->table_name}.$this->id,{$this->table_name}.$this->userId,{$this->table_name}.$this->createdAt,{$this->table_name}.$this->updatedAt, {$this->locations_types_attribute->table_name}.{$this->locations_types_attribute->name} as type , $this->street , $this->city , $this->latLong , $this->nearTo , $this->url , $this->contactPhone";
       
        $condition = "{$this->table_name}.$this->id = $id FOR UPDATE";
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
