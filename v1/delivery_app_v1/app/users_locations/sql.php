<?php
namespace DeliveryMen;

require_once(getPath() . 'tables/users_locations/attribute.php');

class UsersLocationsSql extends \UsersLocationsAttribute
{
    function readByIdsql($id): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $columns = "{$this->table_name}.$this->id, {$this->locations_types_attribute->table_name}.{$this->locations_types_attribute->name} , $this->street , $this->city , $this->latLong , $this->nearTo , $this->url , $this->contactPhone";
        $condition = "$this->id = $id FOR UPDATE";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
