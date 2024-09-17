<?php
namespace UserApp;

require_once (__DIR__ . '/../../../include/tables/projects/attribute.php');

class ProjectsSql extends \ProjectsAttribute
{

    function readStatusSql(): string
    {
        $table_name = $this->table_name;
        $columns = "$this->requestOrderStatus";
        $innerJoin = "";
        $condition = "$this->id = 1";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readMessageSql(): string
    {
        $table_name = $this->table_name;
        $columns = "$this->requestOrderMessage";
        $innerJoin = "";
        $condition = "$this->id = 1";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }

}
