<?php
// $hub_verify_token = "774519161";
// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hub_challenge']) && isset($_GET['hub_verify_token']) && $_GET["hub_verify_token"] === $hub_verify_token) {
//    echo $_GET['hub_challenge'];
//    exit;
// }
require_once __DIR__ . '/../v1/include/shared/helper_functions.php';
$w = new ApiWhatsapp();
// $hub_verify_token = "token123";
// if($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hub_challenge']) && isset($_GET['hub_verify_token']) && $_GET["hub_verify_token"] === $hub_verify_token){
// //   $r = file_get_contents("php://input");
// file_put_contents("r.txt",$r);
// $r = json_decode($r,true);
// }
// $w = new ApiWhatsapp();
// $w->sendMessageText("967774519161", "Hello mustafa");
// exit();

// $w = new ApiWhatsapp();
$phone_number =  $r['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];

$w->sendMessageText("967774519161", $phone_number);

// $r = file_get_contents("php://input");
// // file_put_contents("r22.txt",$r);
// // $r = json_decode($r,true);
//  $r = json_decode($r,true);
// file_put_contents("r.txt",$r['entry'][0]['changes'][0]['value']['messages'][0]['text']['body']);
// // $r = json_decode($r,true);

// // $input = json_decode(file_get_contents('php://input'), true);

// // $sender = $input['entry'][0]['messaging'][0]['sender']['id'];
// $message = $r['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
// $name = $r['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];
// $phone_number =  $r['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];
// $id = $r['entry'][0]['changes'][0]['value']['messages'][0]['id'];
// $list_reply_id = $r['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['list_reply']['id'];
// $list_reply_title = $r['entry'][0]['changes'][0]['value']['messages'][0]['interactive']['list_reply']['title'];
// if($phone_number){
// $sql = "INSERT INTO numbers (number_id, number) VALUES(null, '$phone_number') ON DUPLICATE KEY UPDATE    
// updated_at=CURRENT_TIMESTAMP ";
// $fun->exec_one_sql($sql); 
// }

// if($list_reply_id){
    

// $sql = "SELECT * from message_menus INNER JOIN
// products ON message_menus.message_menu_id = products.message_menu_id
// WHERE message_menus.message_menu_name = '$list_reply_title'";
// $result = $fun->exec_one_sql($sql);
//  $sections = [];
//      if($result){
//       $myArray = array();
//       while ($row = $result->fetch_assoc()) {
//         $myArray[] = $row;
//       }
//     //   $fun_bots->send_plain_message(($sql));
//       $data = $myArray;
//       if(count($data)){
//            for ($i=0; $i < count($data); $i++){
//         $title = $data[$i]['product_main_title'];
//         $row = json_encode(array(
//             "id"  =>  $data[$i]['product_id'],
//             "title" =>  $data[$i]['product_sub_title'],
//             "description" =>  $data[$i]['product_description'],
//             ));
//         $sections[$i]["title"] =$title;
//         $sections[$i]["rows"] =[json_decode($row)];
//         }
        
   
//   $header = $data[0]['message_menu_header'];
//   $body = $data[0]['message_menu_body'];
//   $footer = $data[0]['message_menu_footer'];
//   $button = $data[0]['message_menu_button_name'];
//   // $w->sendMessageText($phone_number, "مرحبا بك  ".$name );
// $w->sendInterActiveList(
//        $phone_number,
//        $header." ".$name,
//        $body,
//        $footer,
//        $button,
//        $sections);
       
//  exit();
//       }
    
//     else{
// $sql = "SELECT *,(SELECT static_value FROM static WHERE static_name = 'contact_info') as contact from messages where product_id = (SELECT product_id from products WHERE product_sub_title = '$list_reply_title')";
// $result = $fun->exec_one_sql($sql);
// if($result){
//      $myArray = array();
//       while ($row = $result->fetch_assoc()) {
//         $myArray[] = $row;
//       }
//     //   $fun_bots->send_plain_message(($sql));
//       $data = $myArray;
//       if(count($data)){
//           $message = $data[0]["message_content"];
//           $link = $data[0]["message_image"];
//           $contact = $data[0]["contact"];
//           $w->sendImage($phone_number, $link);
//           $w->sendMessageText($phone_number, $message."\n".$contact);
         
//       }
// }

        
//     }
    
    
// }  
    
    


 
// }
// else
// if(!empty($message)){
    

// $sql = "SELECT * from message_menus INNER JOIN
// products ON message_menus.message_menu_id = products.message_menu_id
// WHERE message_menus.message_menu_name = 'الرئيسية'";
// $result = $fun->exec_one_sql($sql);
//  $sections = [];
//      if($result){
//       $myArray = array();
//       while ($row = $result->fetch_assoc()) {
//         $myArray[] = $row;
//       }
//     //   $fun_bots->send_plain_message(($sql));
//       $data = $myArray;
//      for ($i=0; $i < count($data); $i++){
//         $title = $data[$i]['product_main_title'];
//         $row = json_encode(array(
//             "id"  =>  $data[$i]['product_id'],
//             "title" =>  $data[$i]['product_sub_title'],
//             "description" =>  $data[$i]['product_description'],
//             ));
//         $sections[$i]["title"] =$title;
//         $sections[$i]["rows"] =[json_decode($row)];
//         }
//      }     
   
//   $header = $data[0]['message_menu_header'];
//   $body = $data[0]['message_menu_body'];
//   $footer = $data[0]['message_menu_footer'];
//   $button = $data[0]['message_menu_button_name'];
 
    
    

// // $w->sendMessageText($phone_number, "مرحبا بك  ".$name );
// $w->sendInterActiveList(
//        $phone_number,
//        $header." ".$name,
//        $body,
//        $footer,
//        $button,
//        $sections);
//  exit();
 
// }




// class ApiWhatsapp
// {
//     private $TOKEN = "EAAKMpuG9ibgBO21lZCLZAGZBqsKYqaRQms054eJw2C5Ihhor9DwlWWzZAsdbmG9UAL2PusuG9U59ddpV95PLPaTW1iLeLvnEovpFJkAeWVOdZBqaAdAXVeK4vzF62GJzEy4YWpbbHuKhSpisZC40nEbpdX6FmKIQZB3VEd0tJVNI6VMv8plrQvZBPMOWCSSiQHZAMNgBTo9jSk22Qvx1Dfp7RranSNiocX3tDpyjP2nfZBtA1Yue8XhIYr";
//     private $VERSION = "v20.0";
//     private $PHONE_NUMBER_ID = "136302776242131";
//     private $BUSINESS_ACCOUNT = "122387020968327";

//     function __construct() {
//         // global $conectado;
//         // $select = "SELECT FIRST 1 * FROM IDSWHATS";
//         // $arrayIds = $conectado->select($select);
//         // foreach ($arrayIds as $ids) {
//         //     $this->TOKEN =$ids->TOKEN;
//         //     $this->VERSION =$ids->VERSION;
//         //     $this->PHONE_NUMBER_ID =$ids->PHONE_NUMBER_ID;
//         //     $this->BUSINESS_ACCOUNT =$ids->BUSINESS_ACCOUNT;
//         // }

//         if (!$this->TOKEN) {
//             throw new Exception("credentials not found");
//         }

//     }

//     function sendMessageText($to, $text) {
        
//         $url = 'https://graph.facebook.com/'.$this->VERSION.'/'.$this->PHONE_NUMBER_ID.'/messages';
//         $data = [
//             "messaging_product" => "whatsapp",
//             "recipient_type" => "individual",
//             "to" => $to,
//             "type" => "text",
//             "text" => [
//                 "preview_url" => false,
//                 "body" => $text
//             ]
//         ];
        
//         $curl = curl_init();
//         curl_setopt($curl, CURLOPT_URL, $url);
//         curl_setopt($curl, CURLOPT_POST, true);
//         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//         $headers = array(
//             "Accept: application/json",
//             "Content-Type: application/json",
//             "Authorization: Bearer " . $this->TOKEN
//         );
//         curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//         curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
//         $resp = curl_exec($curl);
//         curl_close($curl);
//         return json_decode($resp);
//     }
//     function sendImage($to, $link) {
        
//         $url = 'https://graph.facebook.com/'.$this->VERSION.'/'.$this->PHONE_NUMBER_ID.'/messages';
//         $data = [
//             "messaging_product" => "whatsapp",
//             "recipient_type" => "individual",
//             "to" => $to,
//             "type" => "image",
//             "image" => [
//                 "link" => $link
//             ]
//         ];
        
//         $curl = curl_init();
//         curl_setopt($curl, CURLOPT_URL, $url);
//         curl_setopt($curl, CURLOPT_POST, true);
//         curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

//         $headers = array(
//             "Accept: application/json",
//             "Content-Type: application/json",
//             "Authorization: Bearer " . $this->TOKEN
//         );
//         curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//         curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
//         $resp = curl_exec($curl);
//         curl_close($curl);
//         return json_decode($resp);
//     }
// }
