<?php
namespace Manager;

require_once (getPath() . 'tables/offers/attribute.php');

class OffersSql extends \OffersAttribute
{
    function readByNameSql($name): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $innerJoin = "";
        $columns = "$this->table_name.$this->id ,$this->table_name.$this->name";
        $condition = "$this->table_name.$this->name LIKE $name AND DATE($this->expireAt) >  DATE('$date')";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
