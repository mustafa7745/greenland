<?php
namespace Check;

require_once getPath() . 'tables/maintenance_permissions/attribute.php';
class MaintenancePermissionsSql extends \MaintenancePermissionsAttribute
{
    function read_status_sql($permissionId, $type, $appId): string
    {
        $table_name = $this->table_name;
        $columns = $this->status;
        $innerJoin = "";
        $condition = "$this->permissionId = $permissionId and $this->type = $type and $this->appId = $appId";
        ////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
