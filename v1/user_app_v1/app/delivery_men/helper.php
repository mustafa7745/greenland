<?php
namespace UserApp;

require_once 'sql.php';
// 
class DeliveryMenHelper extends DeliveryMenSql
{
  function getData($id)
  {
    $sql = $this->readByIdsql("'$id'");
    $data = shared_execute_read1_no_json_sql($sql);
    if (count($data) != 1) {
      return null;
    }
    return $data[0];
  }
}

$delivery_men_helper = null;
function getDeliveryMenHelper()
{
  global $delivery_men_helper;
  if ($delivery_men_helper == null) {
    $delivery_men_helper = (new DeliveryMenHelper());
  }
  return $delivery_men_helper;
}