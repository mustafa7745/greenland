<?php
namespace Check;

require_once (__DIR__ . '/../../tables/projects/attribute.php');

class ProjectsSql extends \ProjectsAttribute
{
    function readSql($number, $password): string
    {
        $table_name = $this->table_name;
        $columns = "$this->id";
        $innerJoin = "";
        $condition = "$this->number = $number AND $this->password = SHA2($password,512)";
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
