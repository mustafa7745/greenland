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
    $message = $input['entry'][0]['changes'][0]['value']['messages'][0]['text']['body'];
    

    if ($message == "السلام عليكم") {
        $phone_number = $input['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];
    // $w->sendMessageText($phone_number,"fdgfghf");
        if (str_starts_with($phone_number, "967")) {
            if (strlen($phone_number) == 12) {
                $phone = substr($phone_number, 3, 11);
                $name = $input['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];
                require_once __DIR__ . '/../v1/_user.php';
                $user = getUsersHelper()->getData($phone);
                if ($user == null) {
                    $id = uniqid(rand(), false);
                    $password = generateRandomPassword();
                    // $w->sendMessageText($phone_number,"1");
                    getUsersHelper()->addData($id, $phone, $name, $password,$w);
                    $w->sendMessageText($phone_number,"2");

                    $m = "وعليكم السلام ورحمة الله وبركاته";
                    $m = $m . "\n";
                    $m = $m . "مرحبا بك";
                    $m = $m . "\n";
                    $m = "الرقم السري هو: " . $password;
                    $w->sendMessageText($phone_number, $w);
                }
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
// require_once __DIR__ . '/../v1/_user.php';

// getUsersHelper()->addData(10, 7745191617, "musisf", "dggdg","dgg");
// require_once __DIR__ . '/../v1/_user.php';

// $id = uniqid(rand(), false);
// $password = generateRandomPassword();
// // $w->sendMessageText($phone_number,"1");
// getUsersHelper()->addData($id, $phone, $name, $password,$w);

