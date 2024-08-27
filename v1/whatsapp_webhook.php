<?php
// $hub_verify_token = "774519161";
// if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['hub_challenge']) && isset($_GET['hub_verify_token']) && $_GET["hub_verify_token"] === $hub_verify_token) {
//    echo $_GET['hub_challenge'];
//    exit;
// }
require_once __DIR__ . '/../v1/include/shared/helper_functions.php';
$w = new ApiWhatsapp();

$input = file_get_contents('php://input');
$input = json_decode($input, true);

if (isset($input)) {
    $phone_number = $input['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];
    if (str_starts_with($phone_number, "967")) {
        if (strlen($phone_number) == 12) {
            $phone = substr($phone_number, 3,11);
            $name = $input['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];
            $w->sendMessageText($phone_number,"1");

            require_once __DIR__ .'/../v1/_user.php';
            $w->sendMessageText($phone_number,"3");
            $user = getUsersHelper()->getData($phone);
            $w->sendMessageText($phone_number,"5");
            if ($user == null) {
                $w->sendMessageText($phone_number,"no user");
            }else
            $w->sendMessageText($phone_number, json_encode($user));
            $w->sendMessageText($phone_number,"2");

        }
    }
}

// require_once __DIR__ .'/../v1/_user.php';
//             // $w->sendMessageText("967774519161","3");
//             $user = getUsersHelper()->getData("774519161");
//             // $w->sendMessageText("967774519161","5");
//             // if ($user == null) {
//             //     $w->sendMessageText("967774519161","no user");
//             // }else
//             // $w->sendMessageText("967774519161", json_encode($user));
//             // $w->sendMessageText("967774519161","2");


