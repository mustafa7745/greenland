<?php
namespace DeliveryMen;

require_once (getPath() . 'tables/users_locations/attribute.php');

class UsersLocationsSql extends \UsersLocationsAttribute
{
    function readByIdsql($id): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id , $this->street , $this->city , $this->latLong , $this->nearTo , $this->url , $this->contactPhone";
        $innerJoin = "";
        $condition = "$this->id = $id FOR UPDATE";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
