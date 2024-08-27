<?php
require_once __DIR__ . '/../v1/include/tables/users/attribute.php';
require_once __DIR__ . '/../v1/include/check/middleware.php';




class UsersSql extends \UsersAttribute
{
    function addSql($id, $phone, $name, $password): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,$this->phone,`$this->name`,SHA2($password,512),`$this->createdAt`,`$this->updatedAt`)";
        $values = "($id,$name,$phone,$password,'$date','$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
    function readsql($phone): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "$this->phone = $phone";
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
}

class UsersHelper extends UsersSql
{
    function getData($phone)
    {
        $sql = $this->readsql("'$phone'");
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            return null;
        }
        return $data[0];
    }
    function addData($id, $phone, $name, $password,$w)
    {
        shared_execute_sql("START TRANSACTION");
        $sql = $this->addSql("'$id'", "'$phone'", "'$name'", "'$password'");
        $w->sendMessageText("967".$phone,$sql);

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
            $ar = $this->name . "_ID_ERROR";
            $en = $this->name . "_ID_ERROR";
            exitFromScript($ar, $en);
        }
        return $data[0];
    }
}

$users_helper = null;
function getUsersHelper()
{
    global $users_helper;
    if ($users_helper == null) {
        $users_helper = (new UsersHelper());
    }
    return $users_helper;
}

