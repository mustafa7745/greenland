<?php
function exitFromScript($ar, $en, $response_code = 400, $code = 0)
{
  getDB()->conn->close();
  http_response_code($response_code);
  $res = json_encode(array("code" => $code, "message" => array("ar" => $ar, "en" => $en)));
  die($res);
}
function checkLong($value, $len)
{
  if (strlen($value) > $len) {
    $ar = "نص كبيير للغاية";
    $en = "نص كبيير للغاية";
    exitFromScript($ar, $en);
  }
}

function json_is_validate(string $string): bool
{
  json_decode($string);
  return json_last_error() === JSON_ERROR_NONE;
}
function getCurruntDate()
{
  return date('Y-m-d H:i:s');
}

function convertIdsListToStringSql($ids)
{

  $result = "";
  $count = count($ids);
  if ($count == 0) {
    return "''";
  }
  for ($i = 0; $i < $count; $i++) {
    $id = $ids[$i];
    if ($count > ($i + 1)) {
      $result = $result . "'{$id}'" . ",";
    } else
      $result = $result . "'{$id}'";
  }
  return $result;
}

function successReturn()
{
  return ['success' => 'true'];
}
function successReturnValue($value)
{
  return ['success' => $value];
}
function sendMessageToTobic($topic, $title, $body): bool
{
  require_once __DIR__ . "/../projects/helper.php";
  $project = getProjectsHelper()->getDataById(1);
  $json = $project[getProjectsHelper()->serviceAccountKey];
  require_once __DIR__ . "/../../include/send_message.php";
  $sendMessage = new SendFCM();
  return $sendMessage->sendMessageToTobic($json, $topic, $title, $body);
}
function sendMessageToOne($json, $token, $title, $body): bool
{
  $sendMessage = new SendFCM();
  return $sendMessage->sendMessageToOne($json, $token, $title, $body);
}


function INVALID_TOKEN($runApp, $permission)
{
  $ar = "INVALID_TOKEN";
  $en = "INVALID_TOKEN";
  // sleep(5);
  getFailedAttempsLogsHelper()->addData($runApp->deviceSessionIp->id, $permission->id);
  shared_execute_sql("COMMIT");
  exitFromScript($ar, $en, 400, 5002);
}
function TOKEN_NEED_UPDATE()
{
  $ar = "TOKEN_NEED_UPDATE";
  $en = "TOKEN_NEED_UPDATE";
  exitFromScript($ar, $en, 400, 5001);
}
function generateRandomPassword($length = 8)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
  $charactersLength = strlen($characters);
  $randomPassword = '';
  for ($i = 0; $i < $length; $i++) {
    $randomPassword .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomPassword;
}


function createDirectory($full_path_directory)
{
  if (!file_exists($full_path_directory)) {
    if (!mkdir($full_path_directory, 0777, true)) {
      $ar = "FAIL_WHEN_CREATE_DIRECTORY";
      $en = "FAIL_WHEN_CREATE_DIRECTORY";
      exitFromScript($ar, $en);
    }
  }
}
function getIdsFromOrderProducts($orderProjects)
{
  $count = count($orderProjects);
  $ids = '';
  if ($count > 0) {
    for ($i = 0; $i < $count; $i++) {
      // $ids = $ids . $orderProjectsIds[$i+1]["id"];
      if (isset($orderProjects[$i + 1])) {
        if ($count > ($i + 1)) {
          $ids = $ids . "'{$orderProjects[$i + 1]["id"]}'" . ",";
        } else {
          $ids = $ids . "'{$orderProjects[$i + 1]["id"]}'";
        }

        //  print_r($orderProjectsIds[$i+1]["id"]." ");
      } else {
        $ar = "PRODUCTS_NOT_HAVE_OFFICAIL_ORDER";
        $en = "PRODUCTS_NOT_HAVE_OFFICAIL_ORDER";
        exitFromScript($ar, $en);
      }
    }
  } else {
    $ar = "Order_NOT_HAVE_Products";
    $en = "Order_NOT_HAVE_Products";
    exitFromScript($ar, $en);
  }

  // print_r($ids);
  return $ids;

}
function getIdsCountFromOrderProducts($orderProjects)
{
  return count($orderProjects);
}

function getQntFromOrderProducts($orderProjects, $product_id)
{
  $count = count($orderProjects);
  for ($i = 0; $i < $count; $i++) {
    $id = $orderProjects[$i]["id"];
    if ($id == $product_id) {
      return $orderProjects[$i]["qnt"];
    }
  }
  $ar = "QNT_NOT_MATCH_ID";
  $en = "QNT_NOT_MATCH_ID";
  exitFromScript($ar, $en);
}
function getQntFromOrderProducts2($orderProducts, $product_id)
{
  $count = count($orderProducts);
  for ($i = 0; $i < $count; $i++) {
    $id = $orderProducts[$i]["id"];
    if ($id == $product_id) {
      return $orderProducts[$i]["qnt"];
    }
  }
}

function convertToNumber($string)
{
  if (!is_numeric($string)) {
    $ar = "PRICE_NOT_NUMBER";
    $en = "PRICE_NOT_NUMBER";
    exitFromScript($ar, $en);
  }
  return floatval($string);
}

function haversine_distance($lat1, $lon1, $lat2, $lon2)
{
  $radius = 6371; // Earth's radius in kilometers

  // Calculate the differences in latitude and longitude
  $delta_lat = $lat2 - $lat1;
  $delta_lon = $lon2 - $lon1;

  // Calculate the central angles between the two points
  $alpha = $delta_lat / 2;
  $beta = $delta_lon / 2;

  // Use the Haversine formula to calculate the distance
  $a = sin(deg2rad($alpha)) * sin(deg2rad($alpha)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin(deg2rad($beta)) * sin(deg2rad($beta));
  $c = asin(min(1, sqrt($a)));
  $distance = 2 * $radius * $c;

  // Round the distance to four decimal places
  $distance = round($distance, 4);

  return $distance;
}

function getLatLong($data)
{
  return explode(",", $data["latLong"]);
}
function checkIsString($data, $name)
{
  if (!is_string($data)) {
    $ar = "{$name}_NOT_STRING";
    $en = "{$name}_NOT_STRING";
    exitFromScript($ar, $en);
  }
}
function checkIsJson($data, $name)
{
  if (!json_is_validate($data)) {
    $ar = "{$name}_NOT_JSON";
    $en = "{$name}_NOT_JSON";
    exitFromScript($ar, $en);
  }
}

function getIds()
{

  $name = "ids";
  if (!isset(getPostData3()[$name])) {
    $ar = "IDS_EMPTY_OR_NOT_FOUND";
    $en = "IDS_EMPTY_OR_NOT_FOUND";
    exitFromScript($ar, $en);
  }
  $value = getPostData3()[$name];
  return $value;
}


function getColumnImagePath($columns, $key_path)
{
  require_once (__DIR__ . '/../tables/static/sql.php');
  $anonymous_static_sql = new StaticSql();
  return $columns . " , " . $anonymous_static_sql->read_path_icon_app_sql("'$key_path'");
}

$project = null;
function getDeliveryPrice($userLocation)
{
  global $project;
  global $PROJECT_ID;
  /**
   * ADD DELIVERY DATA
   */
  require_once __DIR__ . "/../projects/helper.php";
  if ($project == null) {
    $project = getProjectsHelper()->getDataById($PROJECT_ID);
  }


  $project_lat = (getLatLong($project))[0];
  $project_long = (getLatLong($project))[1];
  // 
  $user_lat = (getLatLong($userLocation))[0];
  $user_long = (getLatLong($userLocation))[1];
  // 
  $distanse = haversine_distance($project_lat, $project_long, $user_lat, $user_long);
  // print_r($distanse);
  $order_price_delivery = 50 * round(($distanse * getPriceDeliveryPer1Km($project)) / 50);
  return $order_price_delivery;
}



class ApiWhatsapp
{
  private $TOKEN = "EAAKMpuG9ibgBOZCw5OS7ZBDlJyI9VuKWaXRxf59wjESYZCbZBNpQPeDSndqVj4P67nDrbOeWjSZCc7EvgkeiPe794A6DOqQZC0xZAk6KJRZAAGHjSk6zIMuLSCz1ghUfncFGjTNwcE323h8sAWF0NuXQ2bjZArZBTCHmLQp3XfY3dVg8tv08xCtypZClYENcoRZCHOqZAuAZDZD";
  private $VERSION = "v20.0";
  private $PHONE_NUMBER_ID = "136302776242131";
  private $BUSINESS_ACCOUNT = "122387020968327";

  function __construct()
  {

    if (!$this->TOKEN) {
      // throw new \Exception("credentials not found");
      $ar = "credentials not found";
      $en = "credentials not found";
      exitFromScript($ar, $en);
    }

  }


  function sendMessageText($to, $text)
  {

    $url = 'https://graph.facebook.com/' . $this->VERSION . '/' . $this->PHONE_NUMBER_ID . '/messages';
    $data = [
      "messaging_product" => "whatsapp",
      "recipient_type" => "individual",
      "to" => $to,
      "type" => "text",
      "text" => [
        "preview_url" => false,
        "body" => $text
      ]
    ];

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $headers = array(
      "Accept: application/json",
      "Content-Type: application/json",
      "Authorization: Bearer " . $this->TOKEN
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    $resp = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if ($resp === false) {
      curl_close($curl);
      return false;
    } else {
      if ($httpCode == 200) {
        curl_close($curl);
        return false;
      } else {
        curl_close($curl);
        return false;
      }
    }
    // return json_decode($resp);
  }


}


function decrypt($dataBase64)
{
  $privatekey = fread(fopen(__DIR__ . "/../keys/privateKey.pem", "r"), 10000);
  openssl_private_decrypt(base64_decode($dataBase64), $decrypted, ($privatekey));
  return ($decrypted);
}
function encrypt($dataText, $publickey)
{
  openssl_public_encrypt($dataText, $encrypted, $publickey);
  return base64_encode($encrypted);
}

function getPublicKeyFormat($key)
{
  return "-----BEGIN PUBLIC KEY-----\n" . chunk_split($key) . "-----END PUBLIC KEY-----";
}
