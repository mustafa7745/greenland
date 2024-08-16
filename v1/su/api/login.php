<?php
require_once "../../include/login/index.php";
class ThisClass
{
  function main(): string
  {
    shared_execute_sql("START TRANSACTION");
    $login = login();
    $runApp = getRunApp($login);
    $loginProject = loginProject(getPermission($login), $runApp);
    $projectLoginToken = getLoginTokenFromUserSessionAndProjectId(getId(getUserSession($login)), getId($loginProject), 1);
    $data2 = json_encode(
      array("token" => getToken($projectLoginToken), "expire_at" => getExpireAt($projectLoginToken))
    );
    $encryptedData = encrypt(
      json_encode($data2),
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