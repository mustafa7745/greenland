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

    $phone_number = $input['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];
    if ($message == "السلام عليكم") {

        if (str_starts_with($phone_number, "967")) {
            if (strlen($phone_number) == 12) {
                $phone = substr($phone_number, 3, 11);
                $name = $input['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];
                require_once __DIR__ . '/../v1/_user.php';
                $user = getUsersHelper()->getData($phone);
                if ($user == null) {
                    $id = uniqid(rand(), false);
                    $password = generateRandomPassword();
                    getUsersHelper()->addData($id, $phone, $name, $password);
                    $m = "وعليكم السلام ورحمة الله وبركاته";
                    $m = $m . "\n";
                    $m = $m . "مرحبا بك";
                    $m = $m . "\n";
                    $m = $m . "الرقم السري هو: ";
                    $w->sendMessageText($phone_number, $m);
                    $w->sendMessageText($phone_number, $password);
                    shared_execute_sql("COMMIT");
                    $w->sendMessageText("967774519161", $name . "->" . $phone);
                    exit;
                }
            }
        } else {
            $id = uniqid(rand(), false);
            (new UsersWhatsappUnregisterHelper())->add2Sql($phone_number);
        }
    } else {
        $message = mysqli_escape_string(getDB()->conn, $message);
        (new UsersWhatsappUnregisterHelper())->addSql($phone_number, $message);
    }

}



