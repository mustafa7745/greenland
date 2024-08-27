<?php
namespace Check;

require_once 'sql.php';

class DevicesSessionsIpsHelper extends DevicesSessionsIpsSql
{
    function getDataByDeviceSessionIdAndIp($deviceSessionId, $ip)
    {
        $sql = $this->readSql("'$deviceSessionId'", "'$ip'");
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) == 1) {
            require_once __DIR__ . '/../../models/DeviceSessionIp.php';

            return new \ModelDeviceSessionIp($data[0]) ;
        }
        return null;
    }
    
    function addData($deviceSessionId, $ip)
    {
        $sql = $this->addSql("'$deviceSessionId'", "'$ip'");
        // print_r($sql);
        // exitFromScript($sql,$sql);
        shared_execute_sql($sql);
        if (mysqli_affected_rows(getDB()->conn) != 1) {
            shared_execute_sql("rollback");
            $ar = "DATA_NOT_EFFECTED";
            $en = "DATA_NOT_EFFECTED";
            exitFromScript($ar, $en);
        }
        return $this->getDataByDeviceSessionIdAndIp($deviceSessionId, $ip);
    }
   
}
$devices_sessions_ips1_helper = null;
function getDevicesSessionIpsHelper()
{
    global $devices_sessions_ips1_helper;
    if ($devices_sessions_ips1_helper == null) {
        $devices_sessions_ips1_helper = new DevicesSessionsIpsHelper();
    }
    return $devices_sessions_ips1_helper;
}
