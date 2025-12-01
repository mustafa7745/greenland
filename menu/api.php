<?php
header('Content-Type: application/json; charset=utf-8');
header("Access-Control-Allow-Origin: *");

// إعدادات الاتصال
$host = 'localhost';
$db = 'u574242705_menu';
$user = 'u574242705_menu'; // غيره باسم المستخدم الخاص بك
$pass = 'K*u@EDw9';     // غيره بكلمة المرور الخاصة بك

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => 'Database Connection Failed']);
    exit;
}// دالة مساعدة لتنفيذ الاستعلامات بأمان
function safeQuery($pdo, $sql, $params = [])
{
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return []; // إرجاع مصفوفة فارغة عند الخطأ بدلاً من إيقاف السكربت
    }
}

// 1. جلب التصنيفات
$categoriesRaw = safeQuery($pdo, "SELECT * FROM storeNestedSections ORDER BY orderNo ASC");
$categories = [];
foreach ($categoriesRaw as $cat) {
    $categories[] = [
        'id' => $cat['id'],
        'name' => $cat['name'] ?? $cat['title'] ?? 'تصنيف',
    ];
}

// 2. جلب المنتجات
$productsRaw = safeQuery($pdo, "SELECT * FROM products WHERE enabled = 1 AND isHidden = 0");
$finalProducts = [];

foreach ($productsRaw as $product) {
    $pid = $product['id'];

    // معالجة الصورة
    $img = $product['cover'] ? 'https://your-domain.com/uploads/' . $product['cover'] : 'https://via.placeholder.com/150';

    // جلب الخيارات (بأمان - لن ينهار إذا الجدول غير موجود)
    $options = safeQuery($pdo, "SELECT * FROM productOptions WHERE product_id = ?", [$pid]);
    $addons = safeQuery($pdo, "SELECT * FROM productAddons WHERE product_id = ?", [$pid]);

    // حساب السعر والخيارات
    $basePrice = 0;
    $modifiers = [];

    // معالجة الخيارات
    if (!empty($options)) {
        $prices = array_column($options, 'price');
        $basePrice = min($prices); // أقل سعر هو السعر الأساسي

        $fmtOpts = [];
        foreach ($options as $o) {
            $fmtOpts[] = ['name' => $o['name'], 'price' => (float) $o['price'], 'checked' => ((float) $o['price'] == $basePrice)];
        }
        $modifiers[] = ['title' => 'اختر الحجم', 'type' => 'radio', 'options' => $fmtOpts];
    }

    // معالجة الإضافات
    if (!empty($addons)) {
        $fmtAdds = [];
        foreach ($addons as $a) {
            $fmtAdds[] = ['name' => $a['name'], 'price' => (float) $a['price'], 'checked' => false];
        }
        $modifiers[] = ['title' => 'إضافات', 'type' => 'checkbox', 'options' => $fmtAdds];
    }

    $finalProducts[] = [
        'id' => $product['id'],
        'cat_id' => $product['storeNestedSectionId'], // تأكد أن هذا العمود موجود في جدول products
        'name' => $product['name'],
        'description' => $product['description'] ?? '',
        'price' => (float) $basePrice,
        'image_url' => $img,
        'modifiers' => $modifiers
    ];
}

echo json_encode([
    'categories' => $categories,
    'products' => $finalProducts
]);
?>