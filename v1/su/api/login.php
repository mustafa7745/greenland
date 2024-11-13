<?php
require_once __DIR__ . '/../../include/login/index_v1.php';
require_once __DIR__ . '/../../include/check/projects_login_tokens/helper.php';

use function Check\getProjectsLoginTokensHelper;

class ThisClass
{

  function main(): string
  {
    shared_execute_sql("START TRANSACTION");
    $login = loginAll(1);
    // $runApp = getRunApp($login);
    $loginProject = $this->loginProject($login->runApp);
    $projectLoginToken = $this->getLoginTokenFromUserSessionAndProjectId($login->userSession->id, getId($loginProject), 10);
    $data2 = json_encode(array("token" => $projectLoginToken->token, "expire_at" => $projectLoginToken->expireAt));
    $encryptedData = encrypt($data2, getPublicKeyFormat($login->runApp->device->publicKey));
    shared_execute_sql("COMMIT");
    return json_encode(
      array(
        "encrypted_data" => $encryptedData
      )
    );
  }
  function loginProject($runApp)
  {
    require_once __DIR__ . '/../../include/check/projects/helper.php';
    $project = Check\getProjectsHelper()->getData(getInputProjectNumber(), getInputProjectPassword());
    if ($project == null) {
      $ar = ",اسم المستخدم او كلمة المرور غير صحيحة";
      // $en = "USER_NAME_OR_PASSWORD_ERROR";
      $en = ",اسم المستخدم او كلمة المرور غير صحيحة";
      // getFailedAttempsLogsHelper()->addData($runApp->deviceSession->id, $permission->id);
      exitFromScript($ar, $en);
    }
    return $project;
  }
  function getLoginTokenFromUserSessionAndProjectId($userSessionId, $projectId, $loginTokenDuration)
  {
    $projectLoginToken = getProjectsLoginTokensHelper()->getData($userSessionId, $projectId);

    if ($projectLoginToken == null) {
      $loginTokenString = uniqid(rand(), false);
      $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
      $projectLoginToken = getProjectsLoginTokensHelper()->addData($userSessionId, $loginTokenString, $projectId, $expireAt);
    } else {
      if (strtotime(getCurruntDate()) > strtotime($projectLoginToken->expireAt)) {
        $loginTokenString = uniqid(rand(), false);
        $expireAt = date('Y-m-d H:i:s', strtotime("+{$loginTokenDuration} minutes"));
        $projectLoginToken = getProjectsLoginTokensHelper()->updateToken($projectLoginToken->id, $loginTokenString, $expireAt);
      }
    }
    return $projectLoginToken;
  }
}

$this_class = new ThisClass();
die($this_class->main());