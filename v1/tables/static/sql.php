<?php
require_once('attribute.php');
class StaticSql extends StaticAttribute
{

    function read_path_icon_app_sql($key): string
    {
        $innerJoin = "";
        $condition = "{$this->table_name}.$this->key = $key";
        return
            "(SELECT CONCAT({$this->r_domain_sql()},{$this->r_value_sql($innerJoin, $condition)}))as $key";
        ;
    }
    private function r_value_sql($innerJoin, $condition): string
    {
        $table_name = $this->table_name;
        $column = "{$this->table_name}.{$this->value}";
        /////
        return shared_read_sql($table_name, $column, $innerJoin, $condition);
    }
    private function r_domain_sql(): string
    {
        $table_name = $this->table_name;
        $column = "{$this->table_name}.{$this->value}";
        /////
        return shared_read_sql($table_name, $column, " ", "{$this->table_name}.$this->key = 'domain'");
    }

}
