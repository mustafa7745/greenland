<?php
namespace UserApp;

require_once (__DIR__ . '/../../../include/tables/offers/attribute.php');

class OffersSql extends \OffersAttribute
{

    function readSql(): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = getColumnImagePath(" * ", "offer_image_path");
        $innerJoin = "";
        $condition = "DATE($this->expireAt) >  DATE('$date') AND $this->isEnabled = 1";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdsSql($ids): string
    {
        $table_name = $this->table_name;
        $innerJoin = "";
        $columns = "*";
        $condition = "$this->table_name.$this->id IN ($ids)";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }

}
