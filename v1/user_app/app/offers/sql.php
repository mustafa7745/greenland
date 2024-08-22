<?php
namespace UserApp;

require_once (getPath() . 'tables/offers/attribute.php');

class OffersSql extends \OffersAttribute
{
   
    function readSql(): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = getColumnImagePath(" * ", "offer_image_path");
        $innerJoin = "";
        $condition = "DATE($this->expireAt) <  DATE('$date')";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
   
}
