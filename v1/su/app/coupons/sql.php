<?php
namespace SU1;

require_once(__DIR__ . '/../../../include/tables/coupons/attribute.php');

class CouponsSql extends \CouponsAttribute
{
    function readSql(): string
    {
        $table_name = $this->table_name;
        $innerJoin = "";
        $columns = "*";
        $condition = "1";
        /////
        return shared_read_sql_pdo($table_name, $columns, $innerJoin, $condition, "{$this->table_name}.$this->createdAt", getOrderedType(), getLimit());
    }
}
