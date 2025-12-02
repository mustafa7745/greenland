<?php
// receiver_bulk.php (السرفر الثانوي)

set_time_limit(600); // زيادة الوقت لأن الصور قد تكون كثيرة
ini_set('memory_limit', '512M');
header('Content-Type: application/json');

// 1. الحماية
$headers = getallheaders();
$sentKey = $headers['X-API-KEY'] ?? '';
if ($sentKey !== "SECRET_KEY_123") {
    http_response_code(403);
    exit(json_encode(['error' => 'Unauthorized 1']));
}

// 2. الاتصال بالقاعدة
$host = 'localhost';
$db = 'u574242705_menu';
$user = 'u574242705_menu'; // غيره باسم المستخدم الخاص بك
$pass = 'K*u@EDw9';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    exit(json_encode(['error' => 'Database connection failed']));
}

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

$report = ['success' => 0, 'failed' => 0, 'images_processed' => 0];

// مصفوفة سحرية لربط الـ ID القديم بالجديد
// الشكل: [ 'Old_ID_10' => 'New_ID_55', 'Old_ID_11' => 'New_ID_56' ]
$idMap = [];

try {
    $pdo->beginTransaction();

    // =======================================================
    // اللوب الأول: المنتجات (Products)
    // =======================================================
    $stmtProd = $pdo->prepare("INSERT INTO products (name, description, price, cover, createdAt) VALUES (?, ?, ?, ?, NOW())");

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
            $prod['name'],
            $prod['description'],
            $prod['price'],
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

    $stmtImg = $pdo->prepare("INSERT INTO productsImages (productId, storeBranchId, image, createdAt) VALUES (?, ?, ?, NOW())");

    foreach ($input['productsImages'] as $imgItem) {
        $oldProductId = $imgItem['productId']; // هذا الـ ID الخاص بالسرفر الرئيسي

        // هل قمنا بإضافة هذا المنتج قبل قليل؟ هل يوجد له ID جديد؟
        if (isset($idMap[$oldProductId])) {
            $newProductId = $idMap[$oldProductId]; // خذ الـ ID الجديد

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
                        $newProductId,
                        $imgItem['storeBranchId'],
                        $localImgName // اسم الملف الجديد
                    ]);

                    $report['images_processed']++;
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
?>