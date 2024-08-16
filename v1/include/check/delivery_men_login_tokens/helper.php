<?php
namespace Check;

require_once 'sql.php';
class DeliveryMenLoginTokensHelper extends DeliveryMenLoginTokensSql
{
    function getData($userSessionId, $deliveryManId)
    {
        $sql = $this->readSql("'$userSessionId'", "'$deliveryManId'");
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

    function addData($userSessionId, $token, $deliveryManId, $expireAt)
    {
        $sql = $this->addSql("'$userSessionId'", "'$token'", "'$deliveryManId'", "'$expireAt'");
        // print_r($sql);
        // exitFromScript($sql,$sql);
        shared_execute_sql($sql);
        if (mysqli_affected_rows(getDB()->conn) != 1) {
            shared_execute_sql("rollback");
            $ar = "DATA_NOT_EFFECTED";
            $en = "DATA_NOT_EFFECTED";
            exitFromScript($ar, $en);
        }
        return $this->getData($userSessionId, $deliveryManId);
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
function getDeliveryMenLoginTokensHelper()
{
    global $projects_login_tokens1_helper;
    if ($projects_login_tokens1_helper == null) {
        $projects_login_tokens1_helper = new DeliveryMenLoginTokensHelper();
    }
    return $projects_login_tokens1_helper;
}
