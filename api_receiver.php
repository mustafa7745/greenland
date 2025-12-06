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
$uploadBase = __DIR__ . '/uploads/images/products';




function addOrUpdateOne($pdo, $uploadBase, $input)
{
    $report = [
        'categories_synced' => 0, // ✅ تمت إضافتها
        'products_synced' => 0,
        'options_synced' => 0,
        'images_synced' => 0,
        'addons_synced' => 0,
        'errors' => []
    ];

    try {
        $pdo->beginTransaction();

        // =======================================================
        // 1️⃣ الأقسام (Categories / NestedSections) - ✅ تمت إضافتها
        // =======================================================
        if (isset($input['storeCategories']['storeNestedSections']) && is_array($input['storeCategories']['storeNestedSections'])) {
            $sqlCat = "INSERT INTO storeNestedSections (id, name, orderNo, orderAt, storeBranchId, isHidden, enabled, createdAt, storeSectionId) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                       ON DUPLICATE KEY UPDATE 
                       name = VALUES(name), orderNo = VALUES(orderNo), isHidden = VALUES(isHidden), 
                       enabled = VALUES(enabled), storeSectionId = VALUES(storeSectionId)";

            $stmtCat = $pdo->prepare($sqlCat);

            foreach ($input['storeCategories']['storeNestedSections'] as $cat) {
                try {
                    $stmtCat->execute([
                        $cat['id'],
                        $cat['name'],
                        $cat['orderNo'],
                        $cat['orderAt'],
                        $cat['storeBranchId'],
                        $cat['isHidden'],
                        $cat['enabled'],
                        $cat['createdAt'],
                        $cat['storeSectionId']
                    ]);
                    $report['categories_synced']++;
                } catch (Exception $e) {
                    $report['errors'][] = "Category {$cat['name']} error: " . $e->getMessage();
                }
            }
        }

        // =======================================================
        // 2️⃣ المنتجات (Products)
        // =======================================================
        if (isset($input['products']) && is_array($input['products'])) {
            $sqlProd = "INSERT INTO products (id, name, description, storeNestedSectionId, cover, createdAt) 
                        VALUES (?, ?, ?, ?, ?, NOW())
                        ON DUPLICATE KEY UPDATE 
                        name = VALUES(name), description = VALUES(description),
                        storeNestedSectionId = VALUES(storeNestedSectionId), cover = VALUES(cover)";

            $stmtProd = $pdo->prepare($sqlProd);

            foreach ($input['products'] as $prod) {
                try {
                    $finalCoverName = null;
                    $existingCover = getLocalImageIfExists($pdo, 'products', $prod['id'], 'cover', "$uploadBase/cover/");

                    if ($existingCover) {
                        $finalCoverName = $existingCover;
                    } else {
                        if (!empty($prod['cover'])) {
                            $prefix = "cover_{$prod['id']}_";
                            $finalCoverName = handleImageDownload($prod['cover'], "$uploadBase/cover/", $prefix);
                        }
                    }

                    $stmtProd->execute([
                        $prod['id'],
                        $prod['name'],
                        $prod['description'],
                        $prod['storeNestedSectionId'] ?? 0,
                        $finalCoverName
                    ]);
                    $report['products_synced']++;
                } catch (Exception $e) {
                    $report['errors'][] = "Product Error: " . $e->getMessage();
                }
            }
        }

        // =======================================================
        // 3️⃣ الخيارات (Options)
        // =======================================================
        if (isset($input['productOptions']) && is_array($input['productOptions'])) {
            $sqlOpt = "INSERT INTO productOptions 
                       (id, productId, name, description, storeNestedSectionId, storeProductViewId,
                       currencyId, price, prePrice, info, isHidden, enabled, orderNo, orderAt, 
                       storeBranchId, cover, createdAt)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                       ON DUPLICATE KEY UPDATE
                       name = VALUES(name), price = VALUES(price), enabled = VALUES(enabled), 
                       cover = VALUES(cover)";

            $stmtOpt = $pdo->prepare($sqlOpt);

            foreach ($input['productOptions'] as $option) {
                try {
                    $finalOptionCover = null;
                    $existingOptionCover = getLocalImageIfExists($pdo, 'productOptions', $option['id'], 'cover', "$uploadBase/cover/");

                    if ($existingOptionCover) {
                        $finalOptionCover = $existingOptionCover;
                    } else {
                        if (!empty($option['cover'])) {
                            $prefixOpt = "option_{$option['id']}_";
                            $finalOptionCover = handleImageDownload($option['cover'], "$uploadBase/cover/", $prefixOpt);
                        }
                    }

                    $stmtOpt->execute([
                        $option['id'],
                        $option['productId'],
                        $option['name'],
                        $option['description'] ?? '',
                        $option['storeNestedSectionId'] ?? null,
                        $option['storeProductViewId'] ?? null,
                        $option['currencyId'] ?? 1,
                        $option['price'] ?? 0,
                        $option['prePrice'] ?? 0,
                        $option['info'] ?? '[]',
                        $option['isHidden'] ?? 0,
                        $option['enabled'] ?? 1,
                        $option['orderNo'] ?? 0,
                        $option['orderAt'] ?? null,
                        $option['storeBranchId'],
                        $finalOptionCover,
                        $option['createdAt']
                    ]);
                    $report['options_synced']++;
                } catch (Exception $e) {
                    $report['errors'][] = "Option Error: " . $e->getMessage();
                }
            }
        }

        // =======================================================
        // 4️⃣ الصور (Images)
        // =======================================================
        if (isset($input['productsImages']) && is_array($input['productsImages'])) {
            $stmtImgInsert = $pdo->prepare("INSERT INTO productImages (productId, storeBranchId, image, createdAt) VALUES (?, ?, ?, NOW())");
            $stmtCheckImg = $pdo->prepare("SELECT id FROM productImages WHERE productId = ? AND image = ?");

            foreach ($input['productsImages'] as $imgItem) {
                try {
                    $prodId = $imgItem['productId'];
                    $imgUrl = $imgItem['image'];
                    $prefix = "gallery_{$prodId}_";
                    $localImgName = handleImageDownload($imgUrl, "$uploadBase/images/", $prefix);

                    if ($localImgName) {
                        $stmtCheckImg->execute([$prodId, $localImgName]);
                        if ($stmtCheckImg->rowCount() == 0) {
                            $stmtImgInsert->execute([$prodId, $imgItem['storeBranchId'], $localImgName]);
                            $report['images_synced']++;
                        }
                    }
                } catch (Exception $e) {
                    $report['errors'][] = "Image Error: " . $e->getMessage();
                }
            }
        }

        // =======================================================
        // 5️⃣ الإضافات (Addons)
        // =======================================================
        if (isset($input['productAddons']) && is_array($input['productAddons'])) {
            $sqlAdd = "INSERT INTO productAddons (id, productId, name, price, isHidden, enabled, orderNo, storeBranchId, orderAt, createdAt) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                       ON DUPLICATE KEY UPDATE
                       name=VALUES(name), price=VALUES(price), isHidden=VALUES(isHidden), enabled=VALUES(enabled)";

            $stmtAdd = $pdo->prepare($sqlAdd);

            foreach ($input['productAddons'] as $addon) {
                try {
                    $stmtAdd->execute([
                        $addon['id'],
                        $addon['productId'],
                        $addon['name'],
                        $addon['price'],
                        $addon['isHidden'],
                        $addon['enabled'],
                        $addon['orderNo'],
                        $addon['storeBranchId'],
                        $addon['orderAt'],
                        $addon['createdAt']
                    ]);
                    $report['addons_synced']++;
                } catch (Exception $e) {
                    $report['errors'][] = "Addon Error: " . $e->getMessage();
                }
            }
        }

        $pdo->commit();
        echo json_encode(['status' => 'success', 'report' => $report]);

    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Sync Error: ' . $e->getMessage()]);
    }
}
function replaceAll($pdo, $uploadBase, $input)
{
    try {
        $pdo->beginTransaction();

        // ----------------------------------------------------
        // أ) عملية التنظيف المسبقة (TRUNCATE)
        // ----------------------------------------------------
        $pdo->exec('TRUNCATE TABLE products'); // يفضل حذف الأعمدة التي تحوي ID لعدم التعارض
        $pdo->exec('TRUNCATE TABLE productImages');
        $pdo->exec('TRUNCATE TABLE storeNestedSections');
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
    INSERT INTO storeNestedSections (id, name, orderNo, orderAt, storeBranchId, isHidden, enabled, createdAt,storeSectionId) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ? ,?)
");
        $stmtProd = $pdo->prepare("INSERT INTO products (id, name, description, storeNestedSectionId, cover, createdAt) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmtImg = $pdo->prepare("INSERT INTO productImages (productId, storeBranchId, image, createdAt) VALUES (?, ?, ?, NOW())");


        // =======================================================
        // 1️⃣ اللوب الأول: إدراج المنتجات (استخدام ID القادم مباشرة)
        // =======================================================

        if (isset($input['storeCategories'])) {
            $storeCategories = $input['storeCategories'];
            if (isset($storeCategories['storeNestedSections'])) {

                foreach ($storeCategories['storeNestedSections'] as $cat) {
                    try {
                        // 2. إدخال الفئة (استخدام ID القادم مباشرة)
                        $stmtCat->execute([
                            $cat['id'], // 1. ID (يجب أن يكون غير auto-increment)
                            $cat['name'], // 2. name
                            $cat['orderNo'], // 4. orderNo
                            $cat['orderAt'], // 5. orderAt (نتأكد أنه تاريخ)
                            $cat['storeBranchId'],
                            $cat['isHidden'],
                            $cat['enabled'],
                            $cat['createdAt'],
                            $cat['storeSectionId']
                        ]);
                    } catch (Exception $e) {
                        $report['errors'][] = "Category {$cat['name']} failed (Sync): " . $e->getMessage();
                    }
                }
            }

        }

        // if (isset($input['storeCategories']) && is_array($input['storeCategories'])) {

        // }

        foreach ($input['products'] as $prod) {
            $oldId = $prod['id'];
            $localCoverName = null;

            try {
                // 1. تحميل صورة الغلاف
                $coverUrl = $prod['cover'];
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



        if (isset($input['productsImages']) && is_array($input['productsImages'])) {
            foreach ($input['productsImages'] as $imgItem) {
                $imgUrl = $imgItem['image'];
                $newProductId = $imgItem['productId'];
                $localImgName = handleImageDownload($imgUrl, "$uploadBase/images/", "gallery_{$newProductId}_");

                if ($localImgName) {
                    $stmtImg->execute([
                        $imgItem['productId'],
                        $imgItem['storeBranchId'],
                        $localImgName
                    ]);
                    $report['images_synced']++;
                }
            }
        }

        if (isset($input['productOptions']) && is_array($input['productOptions'])) {
            foreach ($input['productOptions'] as $option) {

                $stmtOpt = $pdo->prepare("
                    INSERT INTO productOptions 
                    (id, productId, name, description, storeNestedSectionId, storeProductViewId,
                    currencyId, price, prePrice, info, isHidden, enabled, orderNo, orderAt, 
                    storeBranchId, cover, createdAt)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ");

                $optionImgUrl = $option['cover'];
                $localOptionCover = handleImageDownload($optionImgUrl, "$uploadBase/cover/", 'option_');

                $stmtOpt->execute([
                    $option['id'],
                    $option['productId'],
                    $option['name'],
                    $option['description'] ?? '',
                    $option['storeNestedSectionId'] ?? null,
                    $option['storeProductViewId'] ?? null,
                    $option['currencyId'] ?? 1,
                    $option['price'] ?? 0,
                    $option['prePrice'] ?? 0,
                    $option['info'] ?? '[]',
                    $option['isHidden'] ?? 0,
                    $option['enabled'] ?? 1,
                    $option['orderNo'] ?? 0,
                    $option['orderAt'] ?? null,
                    $option['storeBranchId'],
                    $localOptionCover,
                    $option['createdAt'],
                ]);
            }
        }


        // =======================================================
        // 4️⃣ اللوب الرابع: إدراج الإضافات (productAddons)
        // =======================================================
        if (isset($input['productAddons']) && is_array($input['productAddons'])) {
            $stmtAdd = $pdo->prepare("INSERT INTO productAddons (id,productId, name, price, isHidden, enabled, orderNo, storeBranchId, orderAt, createdAt) VALUES (?, ?, ?, ?, ?, ?, ?, ?,?,?)");

            foreach ($input['productAddons'] as $addon) {
                $stmtAdd->execute([
                    $addon['id'],
                    $addon['productId'],
                    $addon['name'],
                    $addon['price'],
                    $addon['isHidden'],
                    $addon['enabled'],
                    $addon['orderNo'],
                    $addon['storeBranchId'],
                    $addon['orderAt'],
                    $addon['createdAt'],
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
}
////
function getLocalImageIfExists($pdo, $table, $id, $colName, $basePath)
{
    // 1. جلب اسم الصورة من قاعدة البيانات
    $stmt = $pdo->prepare("SELECT $colName FROM $table WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // 2. التحقق هل يوجد اسم في القاعدة وهل الملف موجود في السيرفر
    if ($row && !empty($row[$colName])) {
        $fullPath = $basePath . $row[$colName];
        if (file_exists($fullPath)) {
            return $row[$colName]; // أعد الاسم القديم
        }
    }
    return false; // لا توجد صورة أو الملف مفقود
}
function deleteStrategy($pdo, $uploadBase, $input)
{
    $report = ['products_deleted' => 0, 'options_deleted' => 0, 'errors' => []];

    try {
        $pdo->beginTransaction();

        // =======================================================
        // 1️⃣ حذف المنتجات (مع تنظيف صورها وصور خياراتها)
        // =======================================================
        if (isset($input['products']) && is_array($input['products'])) {

            // نجهز استعلامات لجلب أسماء الصور قبل الحذف
            $stmtGetProdCover = $pdo->prepare("SELECT cover FROM products WHERE id = ?");
            $stmtGetGallery = $pdo->prepare("SELECT image FROM productImages WHERE productId = ?");
            $stmtGetOptionCovers = $pdo->prepare("SELECT cover FROM productOptions WHERE productId = ?");

            // استعلام الحذف النهائي
            $stmtDeleteProd = $pdo->prepare("DELETE FROM products WHERE id = ?");

            foreach ($input['products'] as $item) {
                $prodId = $item['id'];

                try {
                    // أ) حذف غلاف المنتج الرئيسي
                    $stmtGetProdCover->execute([$prodId]);
                    $prod = $stmtGetProdCover->fetch(PDO::FETCH_ASSOC);
                    deleteFileIfExists("$uploadBase/cover/", $prod['cover'] ?? null);

                    // ب) حذف صور الجاليري (Gallery) التابعة للمنتج
                    $stmtGetGallery->execute([$prodId]);
                    while ($imgRow = $stmtGetGallery->fetch(PDO::FETCH_ASSOC)) {
                        deleteFileIfExists("$uploadBase/images/", $imgRow['image']);
                    }

                    // ج) حذف صور الخيارات (Options) التابعة للمنتج
                    // لأن حذف المنتج سيحذف الخيارات (Cascade)، يجب أن نحذف صورها من القرص أولاً
                    $stmtGetOptionCovers->execute([$prodId]);
                    while ($optRow = $stmtGetOptionCovers->fetch(PDO::FETCH_ASSOC)) {
                        deleteFileIfExists("$uploadBase/cover/", $optRow['cover']);
                    }

                    // د) حذف السجل من قاعدة البيانات
                    // (سيقوم التتابع Cascade بحذف الصور والخيارات من جداولها تلقائياً)
                    $stmtDeleteProd->execute([$prodId]);

                    if ($stmtDeleteProd->rowCount() > 0) {
                        $report['products_deleted']++;
                    }

                } catch (Exception $e) {
                    $report['errors'][] = "Error deleting product ID $prodId: " . $e->getMessage();
                }
            }
        }

        // =======================================================
        // 2️⃣ حذف خيارات محددة (بشكل مستقل)
        // =======================================================
        // نستخدم هذا الجزء إذا أردنا حذف خيار معين دون حذف المنتج كاملاً
        if (isset($input['productOptions']) && is_array($input['productOptions'])) {

            $stmtGetOptCover = $pdo->prepare("SELECT cover FROM productOptions WHERE id = ?");
            $stmtDeleteOpt = $pdo->prepare("DELETE FROM productOptions WHERE id = ?");

            foreach ($input['productOptions'] as $item) {
                $optId = $item['id'];
                try {
                    // جلب الصورة وحذفها
                    $stmtGetOptCover->execute([$optId]);
                    $opt = $stmtGetOptCover->fetch(PDO::FETCH_ASSOC);
                    deleteFileIfExists("$uploadBase/cover/", $opt['cover'] ?? null);

                    // حذف السجل
                    $stmtDeleteOpt->execute([$optId]);

                    if ($stmtDeleteOpt->rowCount() > 0) {
                        $report['options_deleted']++;
                    }
                } catch (Exception $e) {
                    $report['errors'][] = "Error deleting option ID $optId: " . $e->getMessage();
                }
            }
        }

        // =======================================================
        // 3️⃣ حذف الأقسام (Categories)
        // =======================================================
        if (isset($input['storeCategories']['storeNestedSections']) && is_array($input['storeCategories']['storeNestedSections'])) {
            $stmtDeleteCat = $pdo->prepare("DELETE FROM storeNestedSections WHERE id = ?");

            foreach ($input['storeCategories']['storeNestedSections'] as $cat) {
                try {
                    // ملاحظة: إذا كان القسم يحتوي على منتجات، ستحتاج إلى تفريغها أولاً 
                    // أو الاعتماد على Cascade ليحذف كل المنتجات بداخله (خطر!)
                    $stmtDeleteCat->execute([$cat['id']]);
                } catch (Exception $e) {
                    $report['errors'][] = "Error deleting category ID {$cat['id']}: " . $e->getMessage();
                }
            }
        }

        $pdo->commit();
        echo json_encode(['status' => 'success', 'report' => $report]);

    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Delete Sync Error: ' . $e->getMessage()]);
    }
}

// ==========================================
// دالة مساعدة لحذف الملف
// ==========================================
function deleteFileIfExists($path, $fileName)
{
    if (!empty($fileName)) {
        $fullPath = $path . $fileName;
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}
////
$strategy = $input['strategy'] ?? null; // استخدام ?? null لتجنب Warning

if ($strategy == 'replaceAll') { // ⚠️ تصحيح: استخدام == بدلاً من =
    replaceAll($pdo, $uploadBase, $input);
} elseif ($strategy == 'addOrUpdateOne' || $strategy == 'addOrUpdate') { // ⚠️ تصحيح: استخدام ==
    addOrUpdateOne($pdo, $uploadBase, $input);
} elseif ($strategy == 'delete') { // ⚠️ تصحيح: استخدام ==
    deleteStrategy($pdo, $uploadBase, $input);
} else {
    // حالة عدم تطابق أي استراتيجية
    echo json_encode(['status' => 'error', 'message' => 'Invalid Strategy', 'strategy_received' => $strategy]);
}
?>