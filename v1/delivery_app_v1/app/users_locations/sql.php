<?php
namespace DeliveryMen;

require_once __DIR__ . '/../../../include/tables/users_locations/attribute.php';

class UsersLocationsSql extends \UsersLocationsAttribute
{
    function readByIdsql($id): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $columns = "{$this->table_name}.$this->id,{$this->table_name}.$this->userId,{$this->table_name}.$this->createdAt,{$this->table_name}.$this->updatedAt, {$this->locations_types_attribute->table_name}.{$this->locations_types_attribute->name} as type , $this->street , $this->city , $this->latLong , $this->nearTo , $this->url , $this->contactPhone";
        $condition = "{$this->table_name}.$this->id = $id FOR UPDATE";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
