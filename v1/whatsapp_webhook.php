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
    $message = trim($message);
    if (strlen($message) > 0) {
        $phone_number = $input['entry'][0]['changes'][0]['value']['contacts'][0]['wa_id'];
        require_once __DIR__ . '/../v1/_user.php';
        if ($message == "السلام عليكم") {
            if (str_starts_with($phone_number, "967")) {
                if (strlen($phone_number) == 12) {
                    $phone = substr($phone_number, 3, 11);
                    $name = $input['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];

                    $userHelper = getUsersHelper();
                    $user = $userHelper->getData($phone);
                    if ($user == null) {
                        $id = uniqid(rand(), false);
                        $password = generateRandomPassword();
                        $userHelper->addData($id, $phone, $name, $password);
                        sendMessagePassword($w, $userHelper, $phone, $name, $password, $phone_number);
                    } else {
                        $userId = $user[$userHelper->id];

                        require_once __DIR__ . '/../v1/_managers_users.php';
                        $managerUserHelper = new ManagersUsersHelper();
                        // 
                        $managerUser = $managerUserHelper->getData($userId);
                        if ($managerUser != null) {
                            if ($managerUser[$managerUserHelper->isRequestMessage] != 1) {
                                shared_execute_sql("START TRANSACTION");
                                $managerUserHelper->updateData($managerUser[$managerUserHelper->id]);
                                $password = generateRandomPassword();
                                $userHelper->updatePassword($userId, $password);
                                sendMessagePassword($w, $userHelper, $phone, $name, $password, $phone_number);
                            }
                            exit;
                        }
                        exit;
                    }
                }
            } else {
                (new UsersWhatsappUnregisterHelper())->addData2($phone_number);
                exit;
            }
        } elseif ($message == "نسيت كلمة المرور") {
            if (str_starts_with($phone_number, "967")) {
                if (strlen($phone_number) == 12) {
                    $phone = substr($phone_number, 3, 11);
                    $name = $input['entry'][0]['changes'][0]['value']['contacts'][0]['profile']['name'];

                    $userHelper = getUsersHelper();
                    $user = $userHelper->getData($phone);
                    if ($user != null) {
                        $lastUpdated = $user[getUsersHelper()->updatedAt];
                        $time1 = new DateTime(getCurruntDate());
                        $time2 = new DateTime($lastUpdated);

                        // Calculate the difference
                        $interval = $time1->diff($time2);

                        // Convert the interval to total minutes
                        $totalMinutes = ($interval->h * 60) + $interval->i;

                        if ($totalMinutes > 1) {
                            $userId = $user[getUsersHelper()->id];
                            $password = generateRandomPassword();
                            $userHelper->updatePassword($userId, $password);
                            sendMessageResetPassword($w, $phone, $name, $password, $phone_number);
                        }
                            exit; 
                    }
                }
            } else {
                (new UsersWhatsappUnregisterHelper())->addData2($phone_number);
                exit;
            }

        } else {
            // $w->sendMessageText("967774519161", "tes");
            $message = mysqli_escape_string(getDB()->conn, $message);
            (new UsersWhatsappUnregisterHelper())->addData($phone_number, $message);
            exit;

        }
    } else {
        exit;
    }
}

function sendMessagePassword(ApiWhatsapp $w, $userHelper, $phone, $name, $password, $phone_number)
{
    shared_execute_sql("COMMIT");
    $m = "وعليكم السلام ورحمة الله وبركاته";
    $m = $m . "\n";
    $m = $m . "مرحبا بك";
    $m = $m . "\n";
    $m = $m . "الرقم السري هو: ";
    $w->sendMessageText($phone_number, $m);
    $isSent = $w->sendMessageText($phone_number, $password);
    if ($isSent) {
        $w->sendMessageText("967774519161", $name . "->" . $phone);
    } else {
        (new UsersWhatsappUnregisterHelper())->addData($phone_number, "password is:$password");
    }

    exit;
}
function sendMessageResetPassword(ApiWhatsapp $w, $phone, $name, $password, $phone_number)
{
    shared_execute_sql("COMMIT");
    $m = "مرحبا بك";
    $m = $m . "\n";
    $m = $m . "الرقم السري الجديد هو: ";
    $w->sendMessageText($phone_number, $m);
    $isSent = $w->sendMessageText($phone_number, $password);
    if ($isSent) {
        $w->sendMessageText("967774519161", $name . "RESETED ->" . $phone);
    } else {
        (new UsersWhatsappUnregisterHelper())->addData($phone_number, "password is:$password");
    }

    exit;
}



