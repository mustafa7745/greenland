<?php
namespace UserApp;

require_once 'sql.php';
// 
class ProjectsHelper1 extends ProjectsSql
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

$projects2_helper = null;
function getProjectsHelper1()
{
  global $projects2_helper;
  if ($projects2_helper == null) {
    $projects2_helper = new ProjectsHelper1();
  }
  return $projects2_helper;
}