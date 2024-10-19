<?php
namespace Check;

require_once 'sql.php';

class DevicesHelper extends DevicesSql
{
    function getData($id)
    {
        $sql = $this->readSql("'$id'");
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) == 1) {
            require_once __DIR__ . '/../../models/Device.php';
            return new \ModelDevice($data[0]);
        }
        return null;
    }
    function addData($id, $info, $publicKey)
    {
        $sql = $this->addSql("'$id'", "'$info'", "'$publicKey'");
        // print_r($sql);
        // exitFromScript($sql,$sql);
        shared_execute_sql($sql);
        if (mysqli_affected_rows(getDB()->conn) != 1) {
            shared_execute_sql("rollback");
            $ar = "DATA_NOT_EFFECTED";
            $en = "DATA_NOT_EFFECTED";
            exitFromScript($ar, $en);
        }
        return $this->getData($id);
    }
    function addData_v1($id, $info)
    {
        $sql = $this->addSql_v1("'$id'", "'$info'");
        // print_r($sql);
        // exitFromScript($sql,$sql);
        shared_execute_sql($sql);
        if (mysqli_affected_rows(getDB()->conn) != 1) {
            shared_execute_sql("rollback");
            $ar = "DATA_NOT_EFFECTED";
            $en = "DATA_NOT_EFFECTED";
            exitFromScript($ar, $en);
        }
        return $this->getData($id);
    }
    function updatePublicKey($id, $publicKey)
    {
        $sql = $this->updatePublicKeyql("'$id'", "'$publicKey'");
        // print_r($sql);
        // exitFromScript($sql,$sql);
        shared_execute_sql($sql);
        if (mysqli_affected_rows(getDB()->conn) != 1) {
            shared_execute_sql("rollback");
            $ar = "DATA_NOT_EFFECTED";
            $en = "DATA_NOT_EFFECTED";
            exitFromScript($ar, $en);
        }
        return $this->getData($id);
    }
}
$devices1_helper = null;
function getDevicesHelper()
{
    global $devices1_helper;
    if ($devices1_helper == null) {
        $devices1_helper = new DevicesHelper();
    }
    return $devices1_helper;
}
