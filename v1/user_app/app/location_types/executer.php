<?php
namespace UserApp;

require_once 'helper.php';
class LocationTypesExecuter
{
  function executeGetData()
  {
    $data = getLocationTypesHelper()->getData();
    return $data;
  }

}
$location_types_executer = null;
function getLocationTypesExecuter()
{
  global $location_types_executer;
  if ($location_types_executer == null) {
    $location_types_executer = new LocationTypesExecuter();
  }
  return $location_types_executer;
}

