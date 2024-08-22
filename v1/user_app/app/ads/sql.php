<?php
namespace UserApp;

require_once (getPath() . 'tables/ads/attribute.php');

class AdsSql extends \AdsAttribute
{
    
    function readSql(): string
    {
        $table_name = $this->table_name;
        $columns = getColumnImagePath(" * ", "ads_image_path");
        $innerJoin = "";
        $condition = "1";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
