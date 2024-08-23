<?php
namespace SU1;

require_once (getPath() . 'tables/notifications/attribute.php');

class NotificationsSql extends \NotificationsAttribute
{
    function addSql($id, $title, $description): string
    {
        $date = getCurruntDate();
        $table_name = $this->table_name;
        $columns = "(`$this->id`,`$this->title`,`$this->description`,`$this->createdAt`)";
        $values = "($id,$title,$description,'$date')";
        /////
        return shared_insert_sql($table_name, $columns, $values);
    }
    function readByIdSql($id): string
    {
        $table_name = $this->table_name;
        $columns = "*";
        $innerJoin = "";
        $condition = "$this->id = $id FOR UPDATE";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
    function readSql(): string
    {
        $table_name = $this->table_name;
        $columns = " * ";
        $innerJoin = "";
        $condition = "1";
        /////
        return shared_read_sql($table_name, $columns, $innerJoin, $condition);
    }
}
