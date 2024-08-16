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
// return array of result
function shared_execute_read_no_json_sql($sql)
{
    $db = getDB();
    try {
        $v = $db->conn->query($sql);

        if ($v) {
            $myArray = array();
            while ($row = $v->fetch_assoc()) {
                $myArray[] = $row;
            }
            return new ResultData($myArray);
        }
        ERROR_SQL_COMMAND($db);

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
function shared_execute_read_json_sql($sql, $json)
{
    $db = getDB();
    try {
        $v = $db->conn->query($sql);
        if ($v) {
            $myArray = array();
            while ($row = $v->fetch_assoc()) {
                $myArray[] = $row;
            }
            $data = $myArray;
            for ($i = 0; $i < count($data); $i++) {
                $data[$i] = json_decode($json($data, $i));
            }
            return new ResultData($data);
        }
        ERROR_SQL_COMMAND($db);

    } catch (Exception $e) {
        EXP_SQL($e);
    }
}
function shared_execute_read1_json_sql($sql, $json)
{
    $db = getDB();
    try {
        $v = $db->conn->query($sql);
        if ($v) {
            $myArray = array();
            while ($row = $v->fetch_assoc()) {
                $myArray[] = $row;
            }
            $data = $myArray;
            for ($i = 0; $i < count($data); $i++) {
                $data[$i] = json_decode($json($data, $i),true);
            }
            return $data;
        }
        ERROR_SQL_COMMAND($db);

    } catch (Exception $e) {
        EXP_SQL($e);
    }
}

function get_mysqli_error($db)
{
    return mysqli_error($db);
}

/**
 * Used to Add , update , Delete Sqls
 */
function shared_execute_AUD_server_sql($sql_array)
{
    $db = getDB();
    // print_r($sql_array);
    try {
        $errors = array();
        $array_effected = array();
        $db->conn->query("START TRANSACTION");
        // $this->db->conn->query('SET SESSION time_zone = "+02:00"');

        for ($i = 0; $i < count($sql_array); $i++) {
            // print_r($sql_array[$i]);
            $db->conn->query($sql_array[$i]);
            mysqli_error($db->conn) != "" ? array_push($errors, mysqli_error($db->conn)) : null;
            array_push($array_effected, mysqli_affected_rows($db->conn));
        }

        if (isExecuted($array_effected) && count($errors) == 0) {
            $db->conn->commit();
        } else {
            $db->conn->rollback();
            if (!isExecuted($array_effected)) {
                $ar = "DATA_NOT_EFFECTED";
                $en = "DATA_NOT_EFFECTED";
                exitFromScript($ar, $en);
            } elseif (count($errors) > 0) {
                process_sql_errors($errors, $db);
            } else {
                $ar = "UNKOWN_ERROR";
                $en = "UNKOWN_ERROR";
                exitFromScript($ar, $en);
            }
        }
    } catch (Exception $e) {
        $db->conn->rollback();
        EXP_SQL($e);
    }
}

/**
 * Used to Add , update , Delete Sqls with result data not effected = 5
 */
function shared_execute_AUD_NOT_effected_sql($sql_array)
{
    $db = getDB();
    // print_r($sql_array);
    try {
        $errors = array();
        $array_effected = array();
        $db->conn->query("START TRANSACTION");
        // $this->db->conn->query('SET SESSION time_zone = "+02:00"');

        for ($i = 0; $i < count($sql_array); $i++) {
            // print_r($sql_array[$i]);
            $db->conn->query($sql_array[$i]);
            mysqli_error($db->conn) != "" ? array_push($errors, mysqli_error($db->conn)) : null;
            array_push($array_effected, mysqli_affected_rows($db->conn));
        }

        if (isExecuted($array_effected) && count($errors) == 0) {
            $db->conn->commit();
        } else {
            $db->conn->rollback();
            if (!isExecuted($array_effected)) {
            } elseif (count($errors) > 0) {
                process_sql_errors($errors, $db);
            } else {
                $ar = "UNKOWN_ERROR";
                $en = "UNKOWN_ERROR";
                exitFromScript($ar, $en);
            }
        }
    } catch (Exception $e) {
        $db->conn->rollback();
        EXP_SQL($e);
    }
}

function isExecuted($arr): bool
{
    if (count($arr) > 0) {
        for ($i = 0; $i < count($arr); $i++) {
            if ($arr[$i] == 0)
                return false;
        }
        return true;
    } else {
        return false;
    }
}

function process_sql_errors($errors, $db)
{
    foreach ($errors as $key => $value) {
        if (str_contains($value, "Duplicate entry")) {
            $ar = "DATA_EXIST_BEFORE";
            $en = "DATA_EXIST_BEFORE";
            exitFromScript($ar, $en);
        }
        if (str_contains($value, "Subquery returns more than 1")) {
            $ar = "SUBQUERY_MORE_ONE";
            $en = "SUBQUERY_MORE_ONE";
            exitFromScript($ar, $en);
        }
    }
    ERROR_SQL_COMMAND($db);
}

// User

function shared_execute_add_user_sql($id, $sqlAdd, $sqlRead, ResultData $data)
{
    require_once (getPath() . 'tables/user_insert_operations/sql.php');
    $user_insert_operartion_sql = new UserInsertOperationsSql();
    $sqlUserInsertOperation = $user_insert_operartion_sql->insert_sql("'$id'", "'{$data->getPermissionId()}'", "'{$data->getUserSessionId()}'", $sqlRead);
    $sqlReadMaxDate = $user_insert_operartion_sql->getDiffTime("'{$data->getUserId()}'");
    //
    $resultData = shared_execute_read_no_json_sql($sqlReadMaxDate);
    // print_r("mu");

    // print_r($resultData);
    $diff = $resultData->data[0]["diff"];
    // print_r($sqlReadMaxDate);

    if ($diff == null || $diff > getLimitAdd()) {
        $sql_array = array($sqlAdd, $sqlUserInsertOperation);
        shared_execute_AUD_server_sql($sql_array);
    } else {
        $ar = "CANNOT_DO_OPERATIONS_AT_THIS_TIME";
        $en = "CANNOT_DO_OPERATIONS_AT_THIS_TIME";
        exitFromScript($ar, $en);
    }
}
function shared_execute_add_user_sqls($id, $sql_arrays, $sqlRead, ResultData $data)
{
    require_once (getPath() . 'tables/user_insert_operations/sql.php');
    $user_insert_operartion_sql = new UserInsertOperationsSql();
    $sqlUserInsertOperation = $user_insert_operartion_sql->insert_sql("'$id'", "'{$data->getPermissionId()}'", "'{$data->getUserSessionId()}'", $sqlRead);
    $sqlReadMaxDate = $user_insert_operartion_sql->getDiffTime("'{$data->getUserId()}'");
    //
    $resultData = shared_execute_read_no_json_sql($sqlReadMaxDate);
    // print_r("mu");

    // print_r($resultData);
    $diff = $resultData->data[0]["diff"];
    // print_r($sqlReadMaxDate);

    if ($diff == null || $diff > getLimitAdd()) {
        // $sql_array = array();
        array_push($sql_arrays, $sqlUserInsertOperation);
        // print_r($sql_arrays);
        shared_execute_AUD_server_sql($sql_arrays);
    } else {
        $ar = "CANNOT_DO_OPERATIONS_AT_THIS_TIME";
        $en = "CANNOT_DO_OPERATIONS_AT_THIS_TIME";
        exitFromScript($ar, $en);
    }
}

function sharedAddUserUpdateOperation($user_id, $permissionId, $userSessionId, $updatedId, $perValue, $newValue)
{
    require_once (getPath() . 'tables/user_update_operations/sql.php');
    $user_update_operartion_sql = new \UserUpdateOperationsSql();
    $sqlReadMaxDate = $user_update_operartion_sql->getDiffTime("'$user_id'");
    $maxDateUpdate = shared_execute_read_no_json_sql($sqlReadMaxDate)->data;
    $diff = $maxDateUpdate[0]["diff"];
    if ($diff != null && $diff <= getLimitUpdate()) {
        $ar = "CANNOT_DO_UPDATE_OPERATIONS_AT_THIS_TIME";
        $en = "CANNOT_DO_UPDATE_OPERATIONS_AT_THIS_TIME";
        exitFromScript($ar, $en);
    }
    $perValue = json_encode($perValue, JSON_UNESCAPED_UNICODE);
    $newValue = json_encode($newValue, JSON_UNESCAPED_UNICODE);

    $user_update_operation_id = uniqid(rand(), false);
    $sql = $user_update_operartion_sql->insert_sql("'$user_update_operation_id'", "'$permissionId'", "'$userSessionId'", "'$updatedId'", "'$perValue'", "'$newValue'");


    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
        $ar = "DATA_NOT_EFFECTED_WHEN_ADD_TO_OPERATIONS";
        $en = "DATA_NOT_EFFECTED_WHEN_ADD_TO_OPERATIONS";
        exitFromScript($ar, $en);
    }
}
function sharedAddUserInsertOperation($user_id, $permissionId, $userSessionId, $insertedValues)
{
    
    require_once (getPath() . 'tables/user_insert_operations/sql.php');
    $user_insert_operartion_sql = new \UserInsertOperationsSql();
    
    $sqlReadMaxDate = $user_insert_operartion_sql->getDiffTime("'$user_id'");
    $maxDateUpdate = shared_execute_read_no_json_sql($sqlReadMaxDate)->data;
    $diff = $maxDateUpdate[0]["diff"];
    if ($diff != null && $diff <= getLimitUpdate()) {
        $ar = "CANNOT_DO_INSERT_OPERATIONS_AT_THIS_TIME";
        $en = "CANNOT_DO_INSERT_OPERATIONS_AT_THIS_TIME";
        exitFromScript($ar, $en);
    }
    
    $user_insert_operation_id = uniqid(rand(), false);
    $insertedValues = json_encode($insertedValues, JSON_UNESCAPED_UNICODE);
    $sql = $user_insert_operartion_sql->insert_sql("'$user_insert_operation_id'", "'$permissionId'", "'$userSessionId'", "'$insertedValues'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
        $ar = "DATA_NOT_EFFECTED_WHEN_ADD_TO_OPERATIONS";
        $en = "DATA_NOT_EFFECTED_WHEN_ADD_TO_OPERATIONS";
        exitFromScript($ar, $en);
    }
}

function sharedAddUserdeleteOperation($permissionId, $userSessionId, $deletedValues)
{
    require_once (getPath() . 'tables/user_delete_operations/sql.php');
    $user_delete_operartion_sql = new UserDeleteOperationsSql();
    $user_delete_operation_id = uniqid(rand(), false);
    $deletedValues = json_encode($deletedValues, JSON_UNESCAPED_UNICODE);
    $sql = $user_delete_operartion_sql->insert_sql("'$user_delete_operation_id'", "'$permissionId'", "'$userSessionId'", "'$deletedValues'");
    shared_execute_sql($sql);
    if (mysqli_affected_rows(getDB()->conn) != 1) {
        $ar = "DATA_NOT_EFFECTED_WHEN_ADD_TO_OPERATIONS";
        $en = "DATA_NOT_EFFECTED_WHEN_ADD_TO_OPERATIONS";
        exitFromScript($ar, $en);
    }
}

function shared_execute_delete_user_sql($id, $sqlDelete, $sqlRead, $count, ResultData $data)
{
    $resultData = shared_execute_read_no_json_sql($sqlRead);
    if (count($resultData->data) == $count) {
        $json = json_encode($resultData->data, JSON_UNESCAPED_UNICODE);
        // print_r($json);
        // 
        require_once (getPath() . 'tables/user_delete_operations/sql.php');
        $user_delete_operartion_sql = new UserDeleteOperationsSql();
        $sqlInsertUserDeleteOperation = $user_delete_operartion_sql->insert_sql("'$id'", "'{$data->getPermissionId()}'", "'{$data->getUserSessionId()}'", "'$json'");
        // 
        $sql_array = array($sqlDelete, $sqlInsertUserDeleteOperation);
        shared_execute_AUD_server_sql($sql_array);
    } else {
        $ar = "INCOMPATABLE_DELETD_DATA_COUNT";
        $en = "INCOMPATABLE_DELETD_DATA_COUNT";
        $code = 0;
        exitFromScript($ar, $en, $code);
    }
}
function shared_execute_delete_user_sqls($id, $sql_array, $sqlRead, $count, ResultData $data)
{
    $resultData = shared_execute_read_no_json_sql($sqlRead);
    if (count($resultData->data) == $count) {
        $json = json_encode($resultData->data, JSON_UNESCAPED_UNICODE);
        // print_r($json);
        // 
        require_once (getPath() . 'tables/user_delete_operations/sql.php');
        $user_delete_operartion_sql = new UserDeleteOperationsSql();
        $sqlInsertUserDeleteOperation = $user_delete_operartion_sql->insert_sql("'$id'", "'{$data->getPermissionId()}'", "'{$data->getUserSessionId()}'", "'$json'");
        // 
        array_push($sql_array, $sqlInsertUserDeleteOperation);
        shared_execute_AUD_server_sql($sql_array);
    } else {
        $ar = "INCOMPATABLE_DELETD_DATA_COUNT";
        $en = "INCOMPATABLE_DELETD_DATA_COUNT";
        $code = 0;
        exitFromScript($ar, $en, $code);
    }
}

function shared_execute_update_user_sql($id, $sqlPreValue, $sqlUpdate, $newValue, $updated_id, $sqlReadAfterUpdate, ResultData $data)
{
    require_once (getPath() . 'tables/user_update_operations/sql.php');
    $user_update_operartion_sql = new UserUpdateOperationsSql();

    $sqlReadMaxDate = $user_update_operartion_sql->getDiffTime("'{$data->getUserId()}'");

    $resultData = shared_execute_read_no_json_sql($sqlReadMaxDate);

    $diff = $resultData->data[0]["diff"];
    if ($diff == null || $diff > getLimitUpdate()) {
        $sql1 = $user_update_operartion_sql->insert_sql("'$id'", "'{$data->getPermissionId()}'", "'{$data->getUserSessionId()}'", "'$updated_id'", $sqlPreValue, "'$newValue'");
        // 
        $sql_array = array($sql1, $sqlUpdate);
        // print_r($sql_array);
        shared_execute_AUD_server_sql($sql_array);

        return shared_execute_read_no_json_sql($sqlReadAfterUpdate);
    }
    $ar = "CANNOT_DO_OPERATIONS_AT_THIS_TIME";
    $en = "CANNOT_DO_OPERATIONS_AT_THIS_TIME";
    exitFromScript($ar, $en);
}

function shared_execute_update_user_json_sql($id, $sqlPreValue, $sqlUpdate, $newValue, $updated_id, $sqlReadAfterUpdate, $json, ResultData $data)
{
    require_once (getPath() . 'tables/user_update_operations/sql.php');
    $user_update_operartion_sql = new UserUpdateOperationsSql();

    $sqlReadMaxDate = $user_update_operartion_sql->getDiffTime("'{$data->getUserId()}'");

    $resultData = shared_execute_read_no_json_sql($sqlReadMaxDate);

    $diff = $resultData->data[0]["diff"];
    if ($diff == null || $diff > getLimitUpdate()) {
        $sql1 = $user_update_operartion_sql->insert_sql("'$id'", "'{$data->getPermissionId()}'", "'{$data->getUserSessionId()}'", "'$updated_id'", $sqlPreValue, "'$newValue'");
        // 
        $sql_array = array($sql1, $sqlUpdate);
        // print_r($sql_array);
        shared_execute_AUD_server_sql($sql_array);

        return shared_execute_read_json_sql($sqlReadAfterUpdate, $json);
    }
    $ar = "CANNOT_DO_OPERATIONS_AT_THIS_TIME";
    $en = "CANNOT_DO_OPERATIONS_AT_THIS_TIME";
    exitFromScript($ar, $en);
}