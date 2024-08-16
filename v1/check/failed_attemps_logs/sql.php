<?php

require_once (getPath() . 'tables/failed_attemps_logs/attribute.php');

class FailedAttempsLogsSql extends \FailedAttempsLogsAttribute
{

    function insertSql($deviceSessionIpId, $permissionId): string
    {
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->deviceSessionIpId`,`$this->permissionId`)";
        $values = "(null,$deviceSessionIpId,$permissionId)";
        // print_r(shared_insert_sql($table_name, $columns, $values));
        return shared_insert_sql($table_name, $columns, $values);
    }

    function readSql($deviceSessionIpIds, $permissionId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "COUNT(*)";
        $innerJoin = "";
        $condition = "$this->deviceSessionIpId IN $deviceSessionIpIds and $this->permissionId = $permissionId and $this->createdAt between ('$date' - INTERVAL 1 MINUTE) and '$date'";
        ////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
     

}
