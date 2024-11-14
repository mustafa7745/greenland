<?php
namespace UserApp;

require_once (__DIR__ . '/../../../include/tables/ads/attribute.php');

class AdsSql extends \AdsAttribute
{

    function readSql(): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = getColumnImagePath(" * ", "ads_image_path");
        $innerJoin = "";
        $condition = "DATE($this->expireAt) > DATE('$date') AND $this->isEnabled = $this->ENABLED_STATUS";
        // return shared_read_sql($table_name, $columns, $innerJoin, $condition);
        return shared_read_order_by_sql($table_name, $columns, $innerJoin, $condition, "`$this->createdAt`", "ASC");
    }
}
