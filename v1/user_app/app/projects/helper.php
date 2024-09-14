<?php
namespace UserApp;

require_once 'sql.php';
// 
class ProjectsHelper extends ProjectsSql
{
  function getStatus()
  {
    $sql = $this->readStatusSql();
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "P_ID_ERROR";
      $en = "P_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0][$this->requestOrderStatus];
  }
  function getMessage()
  {
    $sql = $this->readMessageSql();
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = "P_ID_ERROR";
      $en = "P_ID_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0][$this->requestOrderMessage];
  }
}

$projects_helper = null;
function getProjectsHelper()
{
  global $projects_helper;
  if ($projects_helper == null) {
    $projects_helper = new ProjectsHelper();
  }
  return $projects_helper;
}