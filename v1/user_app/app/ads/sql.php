<?php
namespace UserApp;

require_once (getPath() . 'tables/ads/attribute.php');

class AdsSql extends \AdsAttribute
{

    function readSql(): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = getColumnImagePath(" * ", "ads_image_path");
        $innerJoin = "";
        $condition = "DATE($this->createdAt) = DATE('$date') AND $this->isEnabled = $this->ENABLED_STATUS";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
