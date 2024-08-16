<?php
namespace Check;

require_once (getPath() . 'tables/devices_sessions_ips/attribute.php');

class DevicesSessionsIpsSql extends \DevicesSessionsIpsAttribute
{
    function readSql($deviceSessionId, $ip): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id ";
        $innerJoin = "";
        $condition = "$this->deviceSessionId = $deviceSessionId And $this->ip = $ip FOR UPDATE";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIpSql($ip): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id ";
        $innerJoin = "";
        $condition = "$this->ip = $ip";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function addSql($deviceSessionId, $ip): string
    {
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->deviceSessionId`,`$this->ip`)";
        $values = "(NULL,$deviceSessionId,$ip)";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }

}
