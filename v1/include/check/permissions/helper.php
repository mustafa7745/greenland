<?php

require_once 'sql.php';

class PermissionsHelper extends PermissionsSql
{
    function getDataByName($name)
    {
        $sql = $this->readByNameSql("'$name'");
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            $ar = "{$this->table_name}_NAME_ERROR";
            $en = "{$this->table_name}_NAME_ERROR";
            exitFromScript($ar, $en);
        }
        require_once __DIR__ . '/../../models/Permission.php';
        return new ModelPermission($data[0]);
    }
}
$permissions1_helper = null;
function getPermissionsHelper()
{
    global $permissions1_helper;
    if ($permissions1_helper == null) {
        $permissions1_helper = new PermissionsHelper();
    }
    return $permissions1_helper;
}
