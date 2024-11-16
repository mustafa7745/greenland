<?php
namespace UserApp;

require_once __DIR__ . '/../../../include/tables/delivery_men/attribute.php';
// require_once (getPath() . 'tables/delivery_men/attribute.php');

class DeliveryMenSql extends \DeliveryMenAttribute
{
    function readByIdsql($id): string
    {
        $table_name = $this->table_name;
        $innerJoin = $this->INNER_JOIN();
        $columns = "{$this->table_name}.$this->id , {$this->users_attribute->table_name}.{$this->users_attribute->name} , {$this->users_attribute->table_name}.{$this->users_attribute->phone}";
        $condition = "{$this->table_name}.$this->id = $id";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
   
}
