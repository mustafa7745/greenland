<?php
namespace UserApp;

require_once (getPath() . 'tables/delivery_men/attribute.php');

class DeliveryMenSql extends \DeliveryMenAttribute
{
    function readByIdsql($id): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $columns = "{$this->table_name}.$this->id , {$this->users_attribute->table_name}.{$this->users_attribute->name}";
        $condition = "$this->id = $id";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
   
}
