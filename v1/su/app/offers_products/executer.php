<?php
namespace SU1;

require_once 'helper.php';
class OffersProductsExecuter
{
  function executeGetData($offerId)
  {
    return getOffersProductsHelper()->getData($offerId);
  }
  function executeAddData($offerId, $productId, $productQuantity)
  {


    $helper = getOffersProductsHelper();

    /**
     *  START TRANSACTION FOR SQL
     */
    shared_execute_sql("START TRANSACTION");
    $id = uniqid(rand(), false);
    $dataAfterAdd = $helper->addData($id, $offerId, $productId, $productQuantity);
    shared_execute_sql("COMMIT");
    return $dataAfterAdd;
  }

  function executeUpdateQuantity($id, $newValue)
  {
    shared_execute_sql("START TRANSACTION");
    getOffersProductsHelper()->getDataById($id);
    $dataAfterUpdate = getOffersProductsHelper()->updateQuantity($id, $newValue);
    shared_execute_sql("COMMIT");
    return $dataAfterUpdate;
  }
  function executeDeleteData($ids)
  {
    $idsString = convertIdsListToStringSql($ids);

    shared_execute_sql("START TRANSACTION");
    getOffersProductsHelper()->deleteData($idsString, count($ids));
    shared_execute_sql("COMMIT");
    return successReturn();
  }

}

$offers_products_executer = null;
function getOffersProductsExecuter()
{
  global $offers_products_executer;
  if ($offers_products_executer == null) {
    $offers_products_executer = new OffersProductsExecuter();
  }
  return $offers_products_executer;
}