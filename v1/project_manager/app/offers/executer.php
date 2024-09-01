<?php
namespace Manager;

require_once ('helper.php');
class OffersExecuter
{
  function executeGetDataByName($name)
  {
    return getOffersHelper()->getDataByName($name);
  }
}

$offers_executer = null;
function getOffersExecuter()
{
  global $offers_executer;
  if ($offers_executer == null) {
    $offers_executer = new OffersExecuter();
  }
  return $offers_executer;
}