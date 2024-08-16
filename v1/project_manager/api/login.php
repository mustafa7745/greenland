<?php
// 
require_once "../../include/login/index.php";
require_once "../../include/login/managers/index.php";
require_once "../../include/models/UserSession.php";

class ThisClass
{
  function main(): string
  {
    shared_execute_sql("START TRANSACTION");
    $login = login();
    $userSession = new ModelUserSession(getUserSession($login));

    $manager = loginManager($userSession->userId);
    $managerLoginToken = getLoginTokenFromUserSessionAndManagerId($userSession->id, getId($manager), 1);
    // 
    $data2 = json_encode(array("token" => getToken($managerLoginToken), "expire_at" => getExpireAt($managerLoginToken)));
    // print_r($data2);
    $encryptedData = encrypt(
      $data2,
      getPublicKeyFormat(
        getPublicKey(
          getDevice(
            getRunApp($login)
          )
        )
      )
    );
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