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
    $message = $r['entry'][0]['changes'][0];
    $phone_number = $input['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];
    $w->sendMessageText($phone_number,json_encode($message));

    if ($message == "السلام عليكم") {
        if (str_starts_with($phone_number, "967")) {
            if (strlen($phone_number) == 12) {
                $phone = substr($phone_number, 3, 11);
                $name = $input['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];
                $w->sendMessageText($phone_number, "1");

                require_once __DIR__ . '/../v1/_user.php';
                $w->sendMessageText($phone_number, "3");
                $user = getUsersHelper()->getData($phone);
                $w->sendMessageText($phone_number, "5");
                if ($user == null) {
                    $id = uniqid(rand(), false);
                    $password = generateRandomPassword();
                    shared_execute_sql("START TRANSACTION");
                    getUsersHelper()->addData($id, $phone, $name, $password);
                    $m = "وعليكم السلام ورحمة الله وبركاته";
                    $m = $m . "\n";
                    $m = $m . "مرحبا بك";
                    $m = $m . "\n";
                    $m = "الرقم السري هو: " . $password;
                    $w->sendMessageText($phone_number, $m);
                }else
                $w->sendMessageText($phone_number,"nodffd");
            }
        }
    }
}

function generateRandomPassword($length = 5)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}


