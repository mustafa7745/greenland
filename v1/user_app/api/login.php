<?php
require_once "../../include/login/index.php";
class ThisClass
{
  function main(): string
  {
    shared_execute_sql("START TRANSACTION");
    $login = loginAll();
    // $userSession = getUserSession($login);
    // $userSessionId = getId($userSession);
    // // $runApp = getRunApp($login);

    // // $loginProject = loginProject(getPermission($login), $runApp);
    $userLoginToken = getLoginTokenFromUserSession($login->userSession->id, 1);
    // print_r($userLoginToken);

    $data2 = json_encode(
      array("token" => getLoginToken($userLoginToken), "expire_at" => getExpireAt($userLoginToken))
    );
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