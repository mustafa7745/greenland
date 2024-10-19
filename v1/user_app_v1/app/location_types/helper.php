<?php
namespace UserApp;

require_once ('sql.php');
// 
class LocationTypesHelper extends LocationTypesSql
{
  function getData()
  {
    $sql = $this->readSql();
    $data = shared_execute_read1_no_json_sql($sql);
    return $data;
  }
}

$location_types_helper = null;
function getLocationTypesHelper()
{
  global $location_types_helper;
  if ($location_types_helper == null) {
    $location_types_helper = (new LocationTypesHelper());
  }
  return $location_types_helper;
}