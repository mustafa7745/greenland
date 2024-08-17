<?php
require_once (getPath() . 'tables/ids_controller/attribute.php');

class ProjectsSql extends \ProjectsAttribute
{
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = $this->id;
        $innerJoin = "";
        $condition = "$this->id = $id FOR UPDATE";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
