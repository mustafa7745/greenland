<?php
// receiver_bulk.php (هيكلية 4 مسارات بدون ID Map)

set_time_limit(600);
ini_set('memory_limit', '512M');
header('Content-Type: application/json');

require_once "database.php";
require_once "sync_utils.php";

// 1. إعداد الحماية
$sentKey = $_SERVER['HTTP_X_API_KEY'] ?? '';
if (trim($sentKey) !== "SECRET_KEY_123") {
    http_response_code(403);
    exit(json_encode(['error' => 'Unauthorized']));
}

// 2. استقبال البيانات والتحقق
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['products'])) {
    http_response_code(400);
    exit(json_encode(['error' => 'Invalid structure: "products" array missing']));
}

$report = ['products_synced' => 0, 'images_synced' => 0, 'options_synced' => 0, 'addons_synced' => 0, 'errors' => []];
$uploadBase = __DIR__ . '/uploads/images/products';

// الروابط الثابتة لـ S3
$S3_COVER_URL = 'https://apps77.s3.ap-southeast-1.amazonaws.com/covers/products/';
$S3_GALLERY_URL = 'https://apps77.s3.ap-southeast-1.amazonaws.com/products/';
$S3_OPTION_URL = 'https://apps77.s3.ap-southeast-1.amazonaws.com/options/';


try {
    $pdo->beginTransaction();

    // ----------------------------------------------------
    // أ) عملية التنظيف المسبقة (TRUNCATE)
    // ----------------------------------------------------
    $pdo->exec('TRUNCATE TABLE products'); // يفضل حذف الأعمدة التي تحوي ID لعدم التعارض
    $pdo->exec('TRUNCATE TABLE productImages');
    $pdo->exec('TRUNCATE TABLE storeCategories');
    $pdo->exec('TRUNCATE TABLE productAddons');
    $pdo->exec('TRUNCATE TABLE productOptions');
    clearFolder("$uploadBase/cover/");
    clearFolder("$uploadBase/images/");

    // ----------------------------------------------------
    // ب) تجهيز جمل الإدراج (Prepared Statements) - مع إدراج الـ ID يدوياً
    // ----------------------------------------------------
    // ⚠️ لاحظ: أضفنا عمود id في جملة INSERT ونستبدل original_id به
// ... داخل قسم تجهيز جمل الإدراج ...

    $stmtCat = $pdo->prepare("
    INSERT INTO storeCategories (id, name, orderNo, orderAt, storeBranchId, isHidden, enabled, createdAt) 
    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
");
    $stmtProd = $pdo->prepare("INSERT INTO products (id, name, description, storeNestedSectionId, cover, createdAt) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmtImg = $pdo->prepare("INSERT INTO productImages (productId, storeBranchId, image, createdAt) VALUES (?, ?, ?, NOW())");
    $stmtAdd = $pdo->prepare("INSERT INTO productAddons (id,productId, name, price, isHidden, enabled, orderNo, storeBranchId, orderAt, createdAt) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmtOpt = $pdo->prepare("INSERT INTO productOptions (id,productId, name, description, price, prePrice, info, isHidden, enabled, orderNo, orderAt, storeBranchId, cover, createdAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?, NOW())");


    // =======================================================
    // 1️⃣ اللوب الأول: إدراج المنتجات (استخدام ID القادم مباشرة)
    // =======================================================

    if (isset($input['storeCategories']) && is_array($input['storeCategories'])) {
        foreach ($input['storeCategories']['storeCategories'] as $cat) {

            try {
                // 2. إدخال الفئة (استخدام ID القادم مباشرة)
                $stmtCat->execute([
                    $cat['id'], // 1. ID (يجب أن يكون غير auto-increment)
                    $cat['name'], // 2. name
                    $cat['orderNo'] ?? 1, // 4. orderNo
                    $cat['orderAt'] ?? date('Y-m-d H:i:s'), // 5. orderAt (نتأكد أنه تاريخ)
                    $cat['storeBranchId'] ?? 0, // 6. storeBranchId
                    $cat['isHidden'] ?? 0, // 7. isHidden
                    $cat['enabled'] ?? 1 // 8. enabled
                    // 9. createdAt (NOW())
                ]);

                // هنا لا نحتاج لـ idMap لأن الفئات لا تعتمد على شيء، والمنتجات تعتمد عليها
                // وسنستخدم ID الفئة الأصلي للربط في جدول المنتجات مباشرة.

                $report['categories_synced']++;

            } catch (Exception $e) {
                $report['errors'][] = "Category {$cat['name']} failed (Sync): " . $e->getMessage();
            }
        }
    }

    foreach ($input['products'] as $prod) {
        $oldId = $prod['id'];
        $localCoverName = null;

        try {
            // 1. تحميل صورة الغلاف
            $coverUrl = $S3_COVER_URL . ($prod['cover'] ?? '');
            $localCoverName = handleImageDownload($coverUrl, "$uploadBase/cover/", 'cover_');

            // 2. إدخال المنتج والحصول على الـ ID الجديد (الذي هو الـ ID القديم نفسه)
            $stmtProd->execute([
                $prod['id'], // ⬅️ نمرر الـ ID مباشرة لعمود 'id'
                $prod['name'],
                $prod['description'],
                $prod['storeNestedSectionId'] ?? 0,
                $localCoverName
            ]);
            // هنا لا نحتاج lastInsertId() لأننا فرضنا الـ ID

            $report['products_synced']++;

        } catch (Exception $e) {
            $report['errors'][] = "Product {$prod['name']} failed (Sync): " . $e->getMessage();
        }
    }


    // =======================================================
    // 2️⃣ اللوب الثاني: إدراج صور المعرض (productImages)
    // =======================================================
    if (isset($input['productsImages']) && is_array($input['productsImages'])) {
        foreach ($input['productsImages'] as $imgItem) {
            $oldProductId = $imgItem['productId'];

            // بما أن الـ ID لم يتغير (فرضناه)، نستخدم $oldProductId مباشرة
            $newProductId = $oldProductId;

            $imgUrl = $imgItem['image'];
            $localImgName = handleImageDownload($imgUrl, "$uploadBase/images/", "gallery_{$newProductId}_");

            if ($localImgName) {
                $stmtImg->execute([
                    $imgItem['productId'] ?? 0,
                    $imgItem['storeBranchId'] ?? 0,
                    $localImgName
                ]);
                $report['images_synced']++;
            }
        }
    }


    // =======================================================
    // 3️⃣ اللوب الثالث: إدراج الخيارات (productOptions)
    // =======================================================
    if (isset($input['productOptions']) && is_array($input['productOptions'])) {
        foreach ($input['productOptions'] as $option) {
            $oldProductId = $option['productId'];

            // بما أن الـ ID لم يتغير، نستخدم $oldProductId مباشرة
            $newProductId = $oldProductId;

            $optionImgUrl = $S3_OPTION_URL . ($option['cover'] ?? null);
            $localOptionCover = handleImageDownload($optionImgUrl, "$uploadBase/cover/", 'option_');

            $stmtOpt->execute([
                $option['id'],
                $newProductId,
                $option['name'],
                $option['description'] ?? '',
                $option['price'] ?? 0,
                $option['prePrice'] ?? 0,
                $option['info'] ?? '[]',
                $option['isHidden'] ?? 0,
                $option['enabled'] ?? 1,
                $option['orderNo'] ?? 1,
                $option['storeBranchId'] ?? 0,
                $localOptionCover,
            ]);
            $report['options_synced']++;
        }
    }


    // =======================================================
    // 4️⃣ اللوب الرابع: إدراج الإضافات (productAddons)
    // =======================================================
    if (isset($input['productAddons']) && is_array($input['productAddons'])) {
        foreach ($input['productAddons'] as $addon) {
            $oldProductId = $addon['productId'];


            $stmtAdd->execute([
                $addon['id'],
                $addon['productId'],
                $addon['name'],
                $addon['price'] ?? 0,
                $addon['isHidden'] ?? 0,
                $addon['enabled'] ?? 1,
                $addon['orderNo'] ?? 1,
                $addon['storeBranchId'] ?? 0,
            ]);
            $report['addons_synced']++;
        }
    }


    // ----------------------------------------------------
    // د) إنهاء العملية
    // ----------------------------------------------------
    $pdo->commit();
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

    echo json_encode(['status' => 'success', 'report' => $report]);

} catch (Exception $e) {
    $pdo->rollBack();
    $pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Fatal Sync Error: ' . $e->getMessage()]);
}
?>