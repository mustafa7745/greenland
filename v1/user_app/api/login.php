<?php
require_once "../../include/login/index.php";
class ThisClass
{
  function main(): string
  {
    shared_execute_sql("START TRANSACTION");
    $login = loginAll();

    $userLoginToken = getUserLoginTokenFromUserSession($login->userSession->id, 1);
    // print_r($userLoginToken);

    $data2 = json_encode(array("token" => $userLoginToken->loginToken, "expire_at" => $userLoginToken->expireAt));
    // print_r($data2);
    $encryptedData = encrypt($data2, getPublicKeyFormat($login->runApp->device->publicKey));
    // setcookie("mustafa","12345");
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