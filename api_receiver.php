<?php
// receiver_bulk.php (الملف الرئيسي)

set_time_limit(600);
ini_set('memory_limit', '512M');
header('Content-Type: application/json');

// استدعاء ملفات التنظيم
require_once "database.php"; // يوفر لنا $pdo
require_once "sync_utils.php";

// 1. إعداد الحماية
$sentKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
if (trim($sentKey) !== "SECRET_KEY_123") { // تأكد من المفتاح
    http_response_code(403);
    exit(json_encode(['error' => 'Unauthorized']));
}

// 2. استقبال البيانات والتحقق من الهيكلية
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['products'])) {
    http_response_code(400);
    exit(json_encode(['error' => 'Invalid structure: "products" array missing']));
}

$report = ['success' => 0, 'failed' => 0, 'errors' => []];

// تهيئة مسارات الحفظ
$uploadBase = __DIR__ . '/uploads/images/products';
$coverDir = "$uploadBase/cover/";
$imagesDir = "$uploadBase/images/";

try {
    $pdo->beginTransaction();

    // ----------------------------------------------------
    // أ) عملية التنظيف الكاملة (TRUNCATE) للبيانات والجداول
    // ----------------------------------------------------
    // تنفيذ مسح الجداول
    $pdo->exec('TRUNCATE TABLE products');
    $pdo->exec('TRUNCATE TABLE productImages');
    $pdo->exec('TRUNCATE TABLE productAddons'); // افتراض أن هذا الجدول أيضاً يتم مسحه
    $pdo->exec('TRUNCATE TABLE productOptions'); // افتراض أن هذا الجدول أيضاً يتم مسحه

    // تنفيذ مسح المجلدات على السرفر
    clearFolder($coverDir);
    clearFolder($imagesDir);

    // ----------------------------------------------------
    // ب) تجهيز جمل الإدراج (Prepared Statements)
    // ----------------------------------------------------
    // نستخدم original_id بدلاً من id لتجنب تعارض المفاتيح الأساسية (Primary Key)
    $stmtProd = $pdo->prepare("
        INSERT INTO products (original_id, name, description, storeNestedSectionId, cover, createdAt) 
        VALUES (?, ?, ?, ?, ?, NOW())
    ");

    $stmtImg = $pdo->prepare("
        INSERT INTO productImages (productId, storeBranchId, image, createdAt) 
        VALUES (?, ?, ?, NOW())
    ");

    // إكمال جملة إدراج الخيارات (Options) التي كانت ناقصة لديك
    // ... داخل try { ... قبل الدوران على المنتجات ...

    // 1. تعريف بيان الإضافات (Addons)
    $stmtAdd = $pdo->prepare("
    INSERT INTO productAddons (productId, name, price, isHidden, enabled, orderNo, storeBranchId, orderAt, createdAt) 
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
");

    // 2. تعريف بيان الخيارات (Options) 
// (نستخدمه من الكود السابق الذي كان ناقصاً لديك)
    $stmtOpt = $pdo->prepare("
    INSERT INTO productOptions (productId, name, description, price, prePrice, info, isHidden, enabled, orderNo, orderAt, storeBranchId, cover, createdAt) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, NOW())
");

    // ...



    ///
    try {

        
        if (isset($input['products']) && is_array($input['products'])) {
            $coverUrl = $prod['cover'];
            $localCoverName = handleImageDownload($coverUrl, $coverDir, 'cover_');

            // 2. إدخال المنتج والحصول على الـ ID الجديد
            $stmtProd->execute([
                $prod['id'], // الحفظ في original_id
                $prod['name'],
                $prod['description'],
                $prod['storeNestedSectionId'] ?? 0,
                $localCoverName
            ]);
        }

        if (isset($input['productsImages']) && is_array($input['productsImages'])) {
            foreach ($input['productsImages'] as $imgItem) {
                if ($imgItem['productId'] == $oldId) { // التأكد من أن الصورة تتبع المنتج الحالي
                    $imgUrl = $imgItem['image'];
                    $localImgName = handleImageDownload($imgUrl, $imagesDir, "gallery_{$newId}_");

                    if ($localImgName) {
                        $stmtImg->execute([
                            $newId, // الـ ID الجديد
                            $imgItem['storeBranchId'] ?? 0,
                            $localImgName
                        ]);
                        $report['images_processed']++;
                    }
                }
            }
        }

        if (isset($input['productOptions']) && is_array($input['productOptions'])) {
            // نستخدم متغير اللوب $option
            foreach ($input['productOptions'] as $option) {

                // التحقق من أن الخيار يتبع المنتج الحالي
                if (($option['productId'] ?? null) == $oldId) {

                    // ⚠️ منطق تحميل صور الخيارات (إذا كان الخيار نفسه يحمل صورة)
                    $optionImgUrl = $option['cover'];
                    $localOptionCover = handleImageDownload($optionImgUrl, $coverDir, 'option_'); // يجب أن يكون المجلد coverDir جاهزاً

                    $stmtOpt->execute([
                        $newId, // الـ ID الجديد (1)
                        $option['name'], // (2)
                        $option['description'] ?? '', // (3)
                        $option['price'] ?? 0, // (4)
                        $option['prePrice'] ?? 0, // (5)
                        $option['info'] ?? '[]', // (6)
                        $option['isHidden'] ?? 0, // (7)
                        $option['enabled'] ?? 1, // (8)
                        $option['orderNo'] ?? 1, // (9)
                        // NOW()
                        $option['storeBranchId'] ?? 0, // (10)
                        $localOptionCover, // (11) اسم الملف المحلي
                        // NOW()
                    ]);
                }
            }
        }

        if (isset($input['productAddons']) && is_array($input['productAddons'])) {
            // نستخدم متغير اللوب $addon
            foreach ($input['productAddons'] as $addon) {

                // التحقق من أن الإضافة تتبع المنتج الحالي
                if (($addon['productId'] ?? null) == $oldId) {

                    // (هنا لا يوجد تحميل صور، فقط بيانات)
                    $stmtAdd->execute([
                        $newId, // الـ ID الجديد (1)
                        $addon['name'], // (2)
                        $addon['price'] ?? 0, // (3)
                        $addon['isHidden'] ?? 0, // (4)
                        $addon['enabled'] ?? 1, // (5)
                        $addon['orderNo'] ?? 1, // (6)
                        $addon['storeBranchId'] ?? 0, // (7)
                        // NOW() - orderAt
                        // NOW() - createdAt
                    ]);
                }
            }
        }

        $pdo->commit();
    } catch (Exception $e) {

    }



    // إعادة تفعيل الـ Foreign Key
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

    echo json_encode(['status' => 'success', 'report' => $report]);

} catch (Exception $e) {
    $pdo->rollBack();
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Fatal Sync Error: ' . $e->getMessage()]);
}