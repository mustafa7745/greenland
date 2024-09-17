<?php
namespace Check;

require_once (__DIR__ . '/../../tables/apps/attribute.php');

class AppsSql extends \AppsAttribute
{
    function readSql($packageName, $appSha): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id , $this->groupId , $this->projectId , $this->expireAt , $this->version";
        $innerJoin = "";
        $condition = "$this->packageName = $packageName AND $this->sha256 = $appSha";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
