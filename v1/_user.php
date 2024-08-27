<?php 
require_once __DIR__ . '/../v1/include/tables/users/attribute.php' ;
require_once __DIR__ . '/../v1/include/shared/shared_sql.php' ;
require_once __DIR__ . '/../v1/include/shared/shared_executer.php' ;
require_once __DIR__ . '/../v1/include/database_connection/database.php' ;




class UsersSql extends \UsersAttribute
{
    function addSql($deliveryManId): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->deliveryManId`,`$this->createdAt`,`$this->updatedAt`)";
        $values = "(NULL,$deliveryManId,'$date','$date')";
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

