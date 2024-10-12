<?php
// $hub_verify_token = "112233";
// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hub_challenge']) && isset($_GET['hub_verify_token']) && $_GET["hub_verify_token"] === $hub_verify_token) {
//    echo $_GET['hub_challenge'];
//    exit;
// }
$input = file_get_contents('php://input');
$input = json_decode($input, true);
if (isset($input)){
    $phone_number = $input['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];

}


class ApiWhatsapp
{
  private $TOKEN = "EAAPOImiGiE0BOycL9AY4bcrd0qAWdNxoQGZApo31gt4T5tEGOVGWQiCmdak3bGDKkM2dkdb4cGm4ZBCZAvDznnSOZAvFUNNaXB1BpY0yOZAdQ9hApnzIWnx17oAupoUbgd7M3gbQrOZCwU2fheB1ZAZB8LlPLcAMcBZCLE9JUxuzmG1uZAHrWeRwvP68lA537FFHdePXGzKEaLOr2dPCHgkBP9aRvOTpsZD";
  private $VERSION = "v20.0";
  private $PHONE_NUMBER_ID = "432000053329601";
  private $BUSINESS_ACCOUNT = "466665159856051";

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
    curl_close($curl);
    return json_decode($resp);
  }


}