<?php
require_once 'sql.php';

class PermissionsGroupsHelper extends PermissionsGroupsSql
{
    function getData($permissionName, $permissionId, $groupId)
    {
        $sql = $this->readSql("'$permissionId'", "'$groupId'");
        $data = shared_execute_read1_no_json_sql($sql);
        if (count($data) != 1) {
            $ar = "{$permissionName}_NOT_IN_GROUP_PERMISSION";
            $en = "{$permissionName}_NOT_IN_GROUP_PERMISSION";
            exitFromScript($ar, $en);
        }
        // return $data[0];
    }

}
$permissions_groups1_helper = null;
function getPermissionsGroupsHelper()
{
    global $permissions_groups1_helper;
    if ($permissions_groups1_helper == null) {
        $permissions_groups1_helper = new PermissionsGroupsHelper();
    }
    return $permissions_groups1_helper;
}
