<?php

require_once ('sql.php');
// 
class ProjectsHelper extends ProjectsSql
{
  function getDataById($id)
  {
    $sql = $this->readByIdSql("'$id'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      $ar = $this->name . "_IDName_ERROR";
      $en = $this->name . "_IDName_ERROR";
      exitFromScript($ar, $en);
    }
    return $data[0];
  }
}

$projects1_helper = null;
function getIdsControllerHelper()
{
  global $projects1_helper;
  if ($projects1_helper == null) {
    $projects1_helper = new ProjectsHelper();
  }
  return $projects1_helper;
}