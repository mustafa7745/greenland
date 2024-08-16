<?php
require_once "../../include/login/index.php";
require_once "../../include/token/index.php";



class ThisClass
{
  function main(): string
  {
    shared_execute_sql("START TRANSACTION");
    // $login = login();
    $runApp = (new RunApp())->runApp();
    $managerLoginToken = refreshManagerLoginToken($runApp, 5);

    $data2 = json_encode(array("token" => getToken($managerLoginToken), "expire_at" => getExpireAt($managerLoginToken)));
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