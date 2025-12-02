<?php
header("Content-Type: application/json");
$data = json_decode(file_get_contents("php://input"), true);

$uuid = $data["uuid"] ?? null;
$device = $data["device"] ?? null;
$os = $data["os"] ?? null;
$os_version = $data["os_version"] ?? null;
$browser = $data["browser"] ?? null;
$browser_version = $data["browser_version"] ?? null;
$user_agent = $data["user_agent"] ?? null;

if (!$uuid) {
    echo json_encode(["status" => "no uuid"]);
    exit;
}

require_once "database.php";

try {

    // تأكد أن الزائر غير مسجل مسبقاً
    $stmt = $pdo->prepare("SELECT id FROM visitors WHERE uuid = ?");
    $stmt->execute([$uuid]);

    if ($stmt->rowCount() == 0) {
        // سجّل الزيارة الأولى
        $stmt = $pdo->prepare("
            INSERT INTO visitors (uuid, device, os, os_version, browser, browser_version, user_agent, createdAt)
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $uuid,
            $device,
            $os,
            $os_version,
            $browser,
            $browser_version,
            $user_agent
        ]);
    }

    echo json_encode(["status" => "ok"]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "msg" => $e->getMessage()]);
}
