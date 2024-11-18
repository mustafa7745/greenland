<?php

// Used to Read 1)limit of Big data  2)Ordered Data By Colum and Type 3)in User Interface
function shared_read_limit_sql($table_name, $columns, $innerJoin, $orederdBy, $orederdType, $offset, $condition): string
{
    $limit = 3;
    $sql = "(SELECT $columns FROM $table_name $innerJoin WHERE $condition) ORDER BY $orederdBy $orederdType LIMIT $offset,$limit";
    // print_r($sql);
    return $sql;
}
function shared_read_limit2_sql($table_name, $columns, $innerJoin, $orederdBy, $orederdType, $condition, $limit): string
{
    // $limit = getLimitReadCount();
    $sql = "(SELECT $columns FROM $table_name $innerJoin WHERE $condition) ORDER BY $orederdBy $orederdType LIMIT 0 , $limit";
    // print_r($sql);
    return $sql;
}
// function shared_read_limit3_sql($table_name, $columns, $innerJoin, $condition, $limit): string
// {
//     $limit = getLimitReadCount();
//     $sql = "(SELECT $columns FROM $table_name $innerJoin WHERE $condition) LIMIT 0 , $limit";
//     // print_r($sql);
//     return $sql;
// }
function shared_read_limit_custom_sql($table_name, $columns, $innerJoin, $orederdBy, $orederdType, $offset, $limit, $condition): string
{
    $sql = "(SELECT $columns FROM $table_name $innerJoin WHERE $condition) ORDER BY $orederdBy $orederdType LIMIT $offset,$limit";
    // print_r($sql);
    return $sql;
}

// Used to Read 1) One or More data 2)in Server Side 3)in client side
function shared_read_sql($table_name, $columns, $innerJoin, $condition): string
{
    $sql = "(SELECT $columns FROM $table_name $innerJoin WHERE $condition)";
    return $sql;
}
function shared_read_order_by_sql($table_name, $columns, $innerJoin, $condition, $orederdBy, $orederdType): string
{
    $sql = "(SELECT $columns FROM $table_name $innerJoin WHERE $condition) ORDER BY $orederdBy $orederdType";
    return $sql;
}
function shared_read_count_sql($sqll): string
{
    $sql = "(SELECT COUNT(*) FROM $sqll)";
    return $sql;
}
function shared_read_order_sql($table_name, $columns, $innerJoin, $condition, $orederdBy, $orederdType): string
{
    $sql = "(SELECT $columns FROM $table_name $innerJoin WHERE $condition)  ORDER BY $orederdBy $orederdType";
    return $sql;
}
function shared_read_json_sql($table_name, $columns, $innerJoin, $condition): string
{
    $sql = "(SELECT JSON_OBJECT($columns) FROM $table_name $innerJoin WHERE $condition)";
    return $sql;
}

function NATIVE_INNER_JOIN($table_name, $id): string
{
    return "INNER JOIN {$table_name} ON {$table_name}.{$id}";
}
function FORIGN_KEY_ID_INNER_JOIN($nativeInnerJoin, $table_name, $id): string
{
    $inner = "$nativeInnerJoin = {$table_name}.{$id}";
    return $inner;
}

function shared_insert_sql($table_name, $columns, $values): string
{
    $sql = "INSERT INTO `$table_name` $columns VALUES $values";
    return $sql;
}

function shared_update_sql($table_name, $set_query, $condition): string
{
    $sql = "UPDATE `$table_name` $set_query WHERE $condition";
    return $sql;
}
function delete_sql($table_name, $condition): string
{
    $sql = "DELETE FROM `$table_name` WHERE $condition";
    return $sql;
}
// PDO
function shared_read_limit2_sql_pdo($table_name, $columns, $innerJoin, $condition, $orderedBy, $orderedType, $limit)
{
    // Build the SQL query
    return "SELECT $columns FROM $table_name $innerJoin WHERE $condition ORDER BY $orderedBy $orderedType LIMIT 0, $limit";


}
function shared_read_sql_pdo($table_name, $columns, $innerJoin, $condition, $orderedBy, $orderedType, $limit)
{
    // Build the SQL query
    return "SELECT $columns FROM $table_name $innerJoin WHERE $condition ORDER BY $orderedBy $orderedType LIMIT 0, $limit";
}



