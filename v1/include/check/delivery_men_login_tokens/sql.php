<?php
namespace Check;

require_once (__DIR__ . '/../../tables/delivery_men_login_tokens/attribute.php');

class DeliveryMenLoginTokensSql extends \DeliveryMenLoginTokensAttribute
{
    function readSql($userSessionId, $deliveryManId): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->userSessionId = $userSessionId AND $this->deliveryManId = $deliveryManId ";
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
        $condition = "$this->token = $token ";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }

    function addSql($userSessionId, $token, $deliveryManId, $expireAt): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->userSessionId`,`$this->token`,$this->deliveryManId,`$this->expireAt`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NUll,$userSessionId, $token,$deliveryManId, $expireAt , '$date', '$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
    protected function updateTokensql($id, $newValue, $expireAt): string
    {
        $table_name = $this->table_name;
        $set_query = "SET $this->token = $newValue , $this->expireAt = $expireAt";
        $condition = "$this->id = $id";
        /////
        return shared_update_sql($table_name, $set_query, $condition);
    }
}
