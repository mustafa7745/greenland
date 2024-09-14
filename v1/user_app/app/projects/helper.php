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

$projects1_helper = null;
function getProjectsHelper1()
{
  global $projects1_helper;
  if ($projects1_helper == null) {
    $projects1_helper = new ProjectsHelper1();
  }
  return $projects1_helper;
}