<?php
namespace Check;

require_once 'sql.php';

class UsersHelper extends UsersSql
{
    function getData($phone, $password)
    {
        $sql = $this->readSql("'$phone'", "'$password'");
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            return null;
        }
        return $data[0];
    }
}
$users1_helper = null;
function getUsersHelper()
{
    global $users1_helper;
    if ($users1_helper == null) {
        $users1_helper = new UsersHelper();
    }
    return $users1_helper;
}
