<?php
require_once __DIR__ . '/../v1/include/tables/managers_users/attribute.php';
require_once __DIR__ . '/../v1/include/check/middleware_v1.php';


class ManagersUsersHelper extends \ManagersUsersAttribute
{
    protected function updateIsRequestMessageSql($id): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->isRequestMessage = 1, $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
    function readSql($userId): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->userId = $userId";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdsql($id): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->id = $id FOR UPDATE";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    // 
    function getData($userId)
    {
        $sql = $this->readSql("'$userId'");

        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) == 0) {
            return null;
        }
        return $data[0];
    }
    function updateData($id)
    {
        shared_execute_sql("START TRANSACTION");

        $sql = $this->updateIsRequestMessageSql("'$id'");
        shared_execute_sql($sql);
        if (mysqli_affected_rows(getDB()->conn) != 1) {
            shared_execute_sql("rollback");
            exit;
        }
    }
    function getDataById($id)
    {
        $sql = $this->readByIdSql($id);
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            return null;
        }
        return $data[0];
    }
}
