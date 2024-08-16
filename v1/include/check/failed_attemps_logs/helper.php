<?php
use function Check\getDevicesSessionHelper;
use function Check\getDevicesSessionIpsHelper;

require_once 'sql.php';

class FailedAttempsLogsHelper extends FailedAttempsLogsSql
{
    function getData($deviceId, $permissionId)
    {
        $deviceSessionIds = getDevicesSessionHelper()->readByDeviceIdSql("'$deviceId'");
        $ip = getIp();
        $deviceSessionIpIds = getDevicesSessionIpsHelper()->readByIpSql("'$ip'");
        // print_r($this->readSql($deviceSessionIpIds, "'$permissionId'"));

        $sql = "
        SELECT ({$this->readSql($deviceSessionIpIds, "'$permissionId'")}) as ipCount, ({$this->readSql($deviceSessionIds, "'$permissionId'")}) as deviceCount";
        // print_r($sql);
        return shared_execute_read1_no_json_sql($sql)[0];
    }
    function addData($deviceSessionIpId, $permissionId)
    {
        $sql = $this->insertSql("'$deviceSessionIpId'", "'$permissionId'");
        // print_r($sql);
        // exitFromScript($sql,$sql);
        shared_execute_sql($sql);
        if (mysqli_affected_rows(getDB()->conn) != 1) {
            shared_execute_sql("rollback");
            $ar = "DATA_NOT_EFFECTED";
            $en = "DATA_NOT_EFFECTED";
            exitFromScript($ar, $en);
        }
    }
}
$failed_attemps1_helper = null;
function getFailedAttempsLogsHelper()
{
    global $failed_attemps1_helper;
    if ($failed_attemps1_helper == null) {
        $failed_attemps1_helper = new FailedAttempsLogsHelper();
    }
    return $failed_attemps1_helper;
}
