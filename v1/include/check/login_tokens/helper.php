<?php
namespace Check;

require_once 'sql.php';
class loginTokensHelper extends LoginTokensSql
{
    function getData($userSessionId)
    {
        $sql = $this->readSql("'$userSessionId'");
        // print_r($sql);
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            return null;
        }
        require_once __DIR__ . '/../../models/UserLoginToken.php';
        return new \ModelUserLoginToken($data[0]);
    }
    function getDataByToken($token)
    {
        $sql = $this->readByTokenSql("'$token'");
        // print_r($sql);
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            return null;
        }
        require_once __DIR__ . '/../../models/UserLoginToken.php';
        return new \ModelUserLoginToken($data[0]);
    }
    function getDataById($id)
    {
        $sql = $this->readByIdsql("'$id'");
        // print_r($sql);
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            return null;
        }
        require_once __DIR__ . '/../../models/UserLoginToken.php';
        return new \ModelUserLoginToken($data[0]);
    }

    function addData($userSessionId, $loginToken, $expireAt)
    {
        $sql = $this->addSql("'$userSessionId'", "'$loginToken'", "'$expireAt'");
        // print_r($sql);
        // exitFromScript($sql,$sql);
        shared_execute_sql($sql);
        if (mysqli_affected_rows(getDB()->conn) != 1) {
            shared_execute_sql("rollback");
            $ar = "DATA_NOT_EFFECTED";
            $en = "DATA_NOT_EFFECTED";
            exitFromScript($ar, $en);
        }
        return $this->getData($userSessionId);
    }
    function updateToken($id, $newValue, $expireAt)
    {
        $sql = $this->updateTokensql("'$id'", "'$newValue'", "'$expireAt'");
        // print_r($sql);
        // exitFromScript($sql,$sql);
        shared_execute_sql($sql);
        if (mysqli_affected_rows(getDB()->conn) != 1) {
            shared_execute_sql("rollback");
            $ar = "DATA_NOT_EFFECTED_WHEN_UPDATE_TOKEN";
            ;
            $en = "DATA_NOT_EFFECTED_WHEN_UPDATE_TOKEN";
            exitFromScript($ar, $en);
        }
        return $this->getDataById($id);
    }
}
$login_tokens1_helper = null;
function getLoginTokensHelper()
{
    global $login_tokens1_helper;
    if ($login_tokens1_helper == null) {
        $login_tokens1_helper = new loginTokensHelper();
    }
    return $login_tokens1_helper;
}
