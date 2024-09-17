<?php
function shared_execute_sql($sql)
{
    try {
        $v = getDB()->conn->query($sql);
        if (!$v) {
            ERROR_SQL_COMMAND(getDB());
        }
    } catch (Exception $e) {
        EXP_SQL($e);
    }
}

function shared_execute_read1_no_json_sql($sql)
{
    $db = getDB();
    try {
        $v = $db->conn->query($sql);

        if ($v) {
            $myArray = array();
            while ($row = $v->fetch_assoc()) {
                $myArray[] = $row;
            }
            return $myArray;
        }
        ERROR_SQL_COMMAND($db);

    } catch (Exception $e) {
        EXP_SQL($e);
    }
}
