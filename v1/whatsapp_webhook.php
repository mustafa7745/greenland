<?php
// $hub_verify_token = "774519161";
// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hub_challenge']) && isset($_GET['hub_verify_token']) && $_GET["hub_verify_token"] === $hub_verify_token) {
//    echo $_GET['hub_challenge'];
//    exit;
// }
require_once __DIR__ . '/../v1/include/shared/helper_functions.php';
$w = new ApiWhatsapp();

$input = file_get_contents('php://input');
$input = json_decode($input, true);

if (isset($input)) {
    $phone_number = $input['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];
    if (str_starts_with($phone_number, "967")) {
        if (strlen($phone_number) == 12) {
            $phone = substr($phone_number, 3,11);
            $name = $input['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];
            $user = getUsersHelper()->getData($phone);
            if ($user == null) {
                $w->sendMessageText($phone_number,"no user");
            }
            $w->sendMessageText($phone_number, json_encode($user));
        }
    }
}
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

require_once __DIR__ . '/../v1/include/tables/users/attribute.php' ;

