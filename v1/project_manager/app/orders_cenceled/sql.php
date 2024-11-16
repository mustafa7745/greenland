<?php
namespace Manager;

require_once __DIR__ . '/../../../include/tables/orders_cenceled/attribute.php';

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
    function readByOrderIdSql($orderId): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->orderId = $orderId";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
