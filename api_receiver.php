<?php
// receiver_bulk.php (السرفر الثانوي)

set_time_limit(600); // زيادة الوقت لأن الصور قد تكون كثيرة
ini_set('memory_limit', '512M');
header('Content-Type: application/json');

// 1. الحماية
$headers = getallheaders();
$sentKey = '';

if (isset($_SERVER['HTTP_X_API_KEY'])) {
    $sentKey = $_SERVER['HTTP_X_API_KEY'];
} elseif (function_exists('apache_request_headers')) {
    // محاولة بديلة للسرفرات القديمة
    $headers = apache_request_headers();
    // بعض السرفرات ترجع الهيدر بحروف صغيرة x-api-key
    $sentKey = $headers['X-API-KEY'] ?? $headers['x-api-key'] ?? '';
}

// مقارنة المفتاح
if (trim($sentKey) !== "SECRET_KEY_123") {
    http_response_code(403);
    // لنطبع المفتاح الذي وصل لنعرف المشكلة (لأغراض التصحيح فقط)
    exit(json_encode([
        'error' => 'Unauthorized',
        'received_key' => $sentKey, // هذا سيخبرك ماذا وصل بالضبط
        'server_headers' => array_keys($_SERVER) // لنرى ما هي الهيدرات المتاحة
    ]));
}

require_once "database.php";

// 3. استقبال البيانات
$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['products']) || !isset($input['productsImages'])) {
    http_response_code(400);
    exit(json_encode(['error' => 'Invalid structure: missing products or productsImages']));
}

// تجهيز المجلدات
$uploadBase = __DIR__ . '/uploads/images/products';
if (!is_dir("$uploadBase/cover"))
    mkdir("$uploadBase/cover", 0777, true);
if (!is_dir("$uploadBase/images"))
    mkdir("$uploadBase/images", 0777, true);

clearFolder("$uploadBase/cover");
clearFolder("$uploadBase/images");

$report = ['success' => 0, 'failed' => 0, 'images_processed' => 0];

// مصفوفة سحرية لربط الـ ID القديم بالجديد
// الشكل: [ 'Old_ID_10' => 'New_ID_55', 'Old_ID_11' => 'New_ID_56' ]
$idMap = [];

try {
    $pdo->beginTransaction();

    $pdo->exec('TRUNCATE TABLE products');
    $pdo->exec('TRUNCATE TABLE productImages');

    // =======================================================
    // اللوب الأول: المنتجات (Products)
    // =======================================================
    $stmtProd = $pdo->prepare("INSERT INTO products (id,name, description,storeNestedSectionId, cover, createdAt) VALUES (?,?, ? ,?, ?, NOW())");

    foreach ($input['products'] as $prod) {
        $oldId = $prod['id']; // الـ ID في السرفر الرئيسي

        // 1. معالجة صورة الغلاف (Cover)
        $localCoverName = null;
        if (!empty($prod['cover'])) {
            // افترضنا أن الرابط كامل، لو كان اسم ملف فقط أضف الدومين قبله
            $imgUrl = 'https://apps77.s3.ap-southeast-1.amazonaws.com/covers/products/' . $prod['cover'];
            // $imgUrl = $prod['cover'];
            $ext = pathinfo($imgUrl, PATHINFO_EXTENSION) ?: 'jpg';
            // نستخدم uniqid لمنع تكرار الأسماء
            $localCoverName = 'cover_' . uniqid() . '.' . $ext;

            $content = @file_get_contents($imgUrl);
            if ($content) {
                file_put_contents("$uploadBase/cover/$localCoverName", $content);
            } else {
                $localCoverName = null;
            }
        }

        // 2. إدخال المنتج

        $stmtProd->execute([
            $prod['id'],
            $prod['name'],
            $prod['description'],
            $prod['storeNestedSectionId'],
            $localCoverName
        ]);

        // 3. تخزين الـ ID الجديد في الخريطة
        $newId = $pdo->lastInsertId();
        $idMap[$oldId] = $newId; // 👈 هنا السر: ربطنا القديم بالجديد

        $report['success']++;
    }

    // =======================================================
    // اللوب الثاني: صور المنتجات (ProductsImages)
    // =======================================================
    // الآن نستخدم $idMap لمعرفة أي صورة تتبع أي منتج جديد

    $stmtImg = $pdo->prepare("INSERT INTO productImages (productId, storeBranchId, image, createdAt) VALUES (?, ?, ?, NOW())");

    foreach ($input['productsImages'] as $imgItem) {
        $oldProductId = $imgItem['productId']; // هذا الـ ID الخاص بالسرفر الرئيسي

        // هل قمنا بإضافة هذا المنتج قبل قليل؟ هل يوجد له ID جديد؟
        if (isset($idMap[$oldProductId])) {

            // معالجة الصورة
            $localImgName = null;
            // تأكد من اسم الحقل القادم (cover أو image)
            $url = `'https://apps77.s3.ap-southeast-1.amazonaws.com/products/` . $imgItem['image'];

            if (!empty($url)) {
                $ext = pathinfo($url, PATHINFO_EXTENSION) ?: 'jpg';
                $localImgName = 'gallery_' . $newProductId . '_' . uniqid() . '.' . $ext;

                $content = @file_get_contents($url);
                if ($content) {
                    file_put_contents("$uploadBase/images/$localImgName", $content);

                    // الحفظ في القاعدة مع الـ ID الجديد
                    $stmtImg->execute([
                        $imgItem['productId'],
                        $imgItem['storeBranchId'],
                        $localImgName // اسم الملف الجديد
                    ]);
                }
            }
        }
    }

    $pdo->commit();
    echo json_encode(['status' => 'success', 'report' => $report]);

} catch (Exception $e) {
    $pdo->rollBack();
    // (اختياري) هنا يمكن إضافة كود لحذف الصور التي تم رفعها قبل حدوث الخطأ لتنظيف السرفر
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}


// دالة لمسح محتويات مجلد
function clearFolder($folderPath)
{
    if (!is_dir($folderPath))
        return;

    $files = glob($folderPath . '/*');

    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file); // حذف ملف
        } elseif (is_dir($file)) {
            // حذف مجلد فرعي كامل
            array_map('unlink', glob("$file/*"));
            rmdir($file);
        }
    }
}
?>