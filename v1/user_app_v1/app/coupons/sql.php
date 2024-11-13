<?php
namespace UserApp;

require_once (__DIR__ . '/../../../include/tables/coupons/attribute.php');

class CouponsSql extends \CouponsAttribute
{
    function readByCodesql($code): string
    {
        $table_name = $this->table_name;
        $innerJoin = "";
        $columns = "*";
        $condition = "$this->code = $code";
        /////
        return shared_read_order_by_sql($table_name, $columns, $innerJoin, $condition, "{$this->table_name}.$this->createdAt", "DESC");
    }
}
