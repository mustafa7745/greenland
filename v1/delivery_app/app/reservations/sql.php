<?php
namespace DeliveryMen;

require_once (getPath() . 'tables/reservations/attribute.php');

class ReservationsSql extends \ReservationsAttribute
{
    function addSql($deliveryManId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->deliveryManId`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NULL,$deliveryManId,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
    function readsql($deliveryManId): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->deliveryManId = $deliveryManId AND $this->acceptStatus = $this->WAIT_TO_ACCEPT_RESERVED_STATUS";
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
}
