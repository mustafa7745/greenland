<?php
namespace Check;

require_once 'sql.php';

class DevicesSessionsHelper extends DevicesSessionsSql
{
    function getDataByDeviceIdAndAppId($deviceId, $appId)
    {
        $sql = $this->readSql("'$deviceId'", "'$appId'");
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) == 1) {
            require_once __DIR__ . '/../../models/DeviceSession.php';

            return new \ModelDeviceSession($data[0]);
        }
        return null;
    }
    function getDataById($id)
    {
        $sql = $this->readByIdSql("'$id'");
        // print_r($sql);
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            $ar = "{$this->table_name}_ID_ERROR";
            $en = "{$this->table_name}_ID_ERROR";
            exitFromScript($ar, $en);
        }
        return $data[0];
    }
    function addData($deviceId, $appId, $appToken)
    {
        $sql = $this->addSql("'$deviceId'", "'$appId'", "'$appToken'");
        // print_r($sql);
        // exitFromScript($sql,$sql);
        shared_execute_sql($sql);
        if (mysqli_affected_rows(getDB()->conn) != 1) {
            shared_execute_sql("rollback");
            $ar = "DATA_NOT_EFFECTED";
            $en = "DATA_NOT_EFFECTED";
            exitFromScript($ar, $en);
        }
        return $this->getDataByDeviceIdAndAppId($deviceId, $appId);
    }
    function updateAppToken($id, $newValue)
    {
        $sql = $this->updateTokensql("'$id'", "'$newValue'");
        // print_r($sql);
        // exitFromScript($sql,$sql);
        shared_execute_sql($sql);
        if (mysqli_affected_rows(getDB()->conn) != 1) {
            shared_execute_sql("rollback");
            $ar = "DATA_NOT_EFFECTED";
            $en = "DATA_NOT_EFFECTED";
            exitFromScript($ar, $en);
        }
        return $this->getDataById($id);
    }
}
$devices_sessions1_helper = null;
function getDevicesSessionHelper()
{
    global $devices_sessions1_helper;
    if ($devices_sessions1_helper == null) {
        $devices_sessions1_helper = new DevicesSessionsHelper();
    }
    return $devices_sessions1_helper;
}
