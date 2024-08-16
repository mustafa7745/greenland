<?php
namespace Check;

require_once 'sql.php';
class ProjectsLoginTokensHelper extends ProjectsLoginTokensSql
{
    function getData($userSessionId, $projectId)
    {
        $sql = $this->readSql("'$userSessionId'", "'$projectId'");
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

    function addData($userSessionId, $token, $projectId, $expireAt)
    {
        $sql = $this->addSql("'$userSessionId'", "'$token'", "'$projectId'", "'$expireAt'");
        // print_r($sql);
        // exitFromScript($sql,$sql);
        shared_execute_sql($sql);
        if (mysqli_affected_rows(getDB()->conn) != 1) {
            shared_execute_sql("rollback");
            $ar = "DATA_NOT_EFFECTED";
            $en = "DATA_NOT_EFFECTED";
            exitFromScript($ar, $en);
        }
        return $this->getData($userSessionId, $projectId);
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
$projects_login_tokens1_helper = null;
function getProjectsLoginTokensHelper()
{
    global $projects_login_tokens1_helper;
    if ($projects_login_tokens1_helper == null) {
        $projects_login_tokens1_helper = new ProjectsLoginTokensHelper();
    }
    return $projects_login_tokens1_helper;
}
