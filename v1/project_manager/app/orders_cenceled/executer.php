<?php
namespace Manager;

require_once ('helper.php');
class OrdersCenceledExecuter
{
  // function executeGetData($phone)
  // {
  //   return getOrdersCenceledHelper()->getData($phone);
  // }
  // function executeGetDataById($id)
  // {
  //   return getOrdersCenceledHelper()->getDataById($id);
  // }
 
}
$orders_cenceled_executer = null;
function getOrdersCenceledExecuter()
{
  global $orders_cenceled_executer;
  if ($orders_cenceled_executer == null) {
    $orders_cenceled_executer = (new OrdersCenceledExecuter());
  }
  return $orders_cenceled_executer;
}

