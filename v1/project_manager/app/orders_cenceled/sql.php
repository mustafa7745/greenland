<?php
namespace Manager;

require_once (getPath() . 'tables/orders_cenceled/attribute.php');

class OrdersCenceledSql extends \OrdersCenceledAttribute
{
    function addSql($orderId, $description): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->orderId`,`$this->description`,`$this->createdAt`)";
        $values = "(NULL,$orderId,$description,'$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
}
