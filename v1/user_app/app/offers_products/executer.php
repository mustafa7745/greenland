<?php
namespace UserApp;

require_once 'helper.php';
class OffersProductsExecuter
{
  function executeGetData($offerId)
  {
    return getOffersProductsHelper()->getData($offerId);
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