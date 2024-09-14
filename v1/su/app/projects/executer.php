<?php
namespace SU1;

require_once 'helper.php';
class ProjectsExecuter
{
  function executeGetData($projectId)
  {
    return getProjectsHelper()->getDataById($projectId);
  }
  function executeUpdatePassword($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    ;
    getProjectsHelper()->getDataById($id);
    $dataAfterUpdate = getProjectsHelper()->updatePassword($id, $newValue);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateDeviceId($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    ;
    getProjectsHelper()->getDataById($id);
    $dataAfterUpdate = getProjectsHelper()->updateDeviceId($id, $newValue);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateLatLong($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    ;
    getProjectsHelper()->getDataById($id);
    $dataAfterUpdate = getProjectsHelper()->updateLatLong($id, $newValue);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateRequestOrderMessage($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    ;
    getProjectsHelper()->getDataById($id);
    $dataAfterUpdate = getProjectsHelper()->updateRequestOrderMessage($id, $newValue);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdatePriceDeliveryPer1km($id, $newValue)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    ;
    getProjectsHelper()->getDataById($id);
    $dataAfterUpdate = getProjectsHelper()->updatePriceDeliveryPer1km($id, $newValue);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeUpdateRequestOrderStatus($id)
  {

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    ;
    getProjectsHelper()->getDataById($id);
    $dataAfterUpdate = getProjectsHelper()->updateRequestOrderStatus($id);
    /**
     * COMMIT
     */
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
}

$projects_executer = null;
function getProjectsExecuter()
{
  global $projects_executer;
  if ($projects_executer == null) {
    $projects_executer = new ProjectsExecuter();
  }
  return $projects_executer;
}