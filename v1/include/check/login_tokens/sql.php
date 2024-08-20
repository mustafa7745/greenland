<?php
namespace Check;

require_once (getPath() . 'tables/login_tokens/attribute.php');

class LoginTokensSql extends \LoginTokensAttribute
{
    function readSql($userSessionId): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->userSessionId = $userSessionId ";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->id = $id ";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readByTokenSql($token): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->loginToken = $token ";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }

    function addSql($userSessionId, $loginToken, $expireAt): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->userSessionId`,`$this->loginToken`,`$this->expireAt`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NUll,$userSessionId, $loginToken, $expireAt,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
    protected function updateTokensql($id, $newValue, $expireAt): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $set_query = "SET $this->loginToken = $newValue , $this->expireAt = $expireAt , $this->updatedAt = '$date'";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
}
