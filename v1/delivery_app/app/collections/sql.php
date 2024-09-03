<?php
namespace DeliveryMen;

require_once (getPath() . 'tables/collections/attribute.php');

class CollectionsSql extends \CollectionsAttribute
{
    function addSql($userId, $deliveryManId, $sum): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->deliveryManId`,`$this->orderId`,`$this->price`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NULL,$deliveryManId ,$userId,$sum,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
}
