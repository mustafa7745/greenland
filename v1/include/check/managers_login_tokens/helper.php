<?php
namespace Check;

require_once 'sql.php';
class ManagersLoginTokensHelper extends ManagersLoginTokensSql
{
    function getData($userSessionId, $managerId)
    {
        $sql = $this->readSql("'$userSessionId'", "'$managerId'");
        // print_r($sql);
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            return null;
        }
        return $data[0];
    }
    function getDataByToken($token)
    {
        $sql = $this->readByTokenSql("'$token'");
        // print_r($sql);
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            return null;
        }
        return $data[0];
    }
    function getDataById($id)
    {
        $sql = $this->readByIdsql("'$id'");
        // print_r($sql);
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            return null;
        }
        return $data[0];
    }

    function addData($userSessionId, $token, $managerId, $expireAt)
    {
        $sql = $this->addSql("'$userSessionId'", "'$token'", "'$managerId'", "'$expireAt'");
        // print_r($sql);
        // exitFromScript($sql,$sql);
        shared_execute_sql($sql);
        if (mysqli_affected_rows(getDB()->conn) != 1) {
            shared_execute_sql("rollback");
            $ar = "DATA_NOT_EFFECTED";
            $en = "DATA_NOT_EFFECTED";
            exitFromScript($ar, $en);
        }
        return $this->getData($userSessionId, $managerId);
    }
    function updateToken($id, $newValue, $expireAt)
    {
        $sql = $this->updateTokensql("'$id'", "'$newValue'", "'$expireAt'");
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
$managers_login_tokens1_helper = null;
function getManagersLoginTokensHelper()
{
    global $managers_login_tokens1_helper;
    if ($managers_login_tokens1_helper == null) {
        $managers_login_tokens1_helper = new ManagersLoginTokensHelper();
    }
    return $managers_login_tokens1_helper;
}
