<?php
require_once "../../include/login/index.php";
require_once "../../include/token/index.php";



class ThisClass
{
  function main(): string
  {
    shared_execute_sql("START TRANSACTION");
    // $login = login();
    $runApp = getMainRunApp();
    // print_r($runApp);
    $loginToken = refreshUserLoginToken($runApp, 1);
    // print_r($loginToken);
    $data2 = json_encode(
      array("token" => getLoginToken($loginToken), "expire_at" => getExpireAt($loginToken))
    );
    $encryptedData = encrypt(
      $data2,
      getPublicKeyFormat(
        getPublicKey(
          getDevice(
            $runApp
          )
        )
      )
    );
    // sleep(5);
    shared_execute_sql("COMMIT");
    return json_encode(
      array(
        "encrypted_data" => $encryptedData
      )
    );
  }
}

$this_class = new ThisClass();
die($this_class->main());