<?php
namespace Check;

require_once 'sql.php';

class UsersSessionsHelper extends UsersSessionSql
{
    function getData($userId, $deviceSessionId)
    {
        $sql = $this->readSql("'$userId'", "'$deviceSessionId'");
        // print_r($sql);
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            return null;
        }
        return $data[0];
    }
    function getDataById($id)
    {
        $sql = $this->readByIdSql("'$id'");
        // print_r($sql);
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            $ar = "ID_ERROR";
            $en = "ID_ERROR";
            exitFromScript($ar, $en);
        }
        return $data[0];
    }

    function addData($userId, $deviceSessionId)
    {
        $sql = $this->addSql("'$userId'", "'$deviceSessionId'");
        // print_r($sql);
        // exitFromScript($sql,$sql);
        shared_execute_sql($sql);
        if (mysqli_affected_rows(getDB()->conn) != 1) {
            shared_execute_sql("rollback");
            $ar = "DATA_NOT_EFFECTED";
            $en = "DATA_NOT_EFFECTED";
            exitFromScript($ar, $en);
        }
        return $this->getData($userId, $deviceSessionId);
    }
}
$users_sessions1_helper = null;
function getUsersSessionsHelper()
{
    global $users_sessions1_helper;
    if ($users_sessions1_helper == null) {
        $users_sessions1_helper = new UsersSessionsHelper();
    }
    return $users_sessions1_helper;
}
