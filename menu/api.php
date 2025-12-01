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
}

// 1. جلب التصنيفات
$stmt = $pdo->query("SELECT * FROM storeNestedSections ORDER BY orderNo");
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2. جلب المنتجات
$stmt = $pdo->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. جلب الخيارات لكل منتج (Loop)
// ملاحظة: في المشاريع الكبيرة يفضل استخدام JOINs لتقليل الاستعلامات
// foreach ($products as &$product) {
//     // جلب المجموعات (Groups)
//     $stmt = $pdo->prepare("SELECT * FROM modifier_groups WHERE product_id = ?");
//     $stmt->execute([$product['id']]);
//     $groups = $stmt->fetchAll(PDO::FETCH_ASSOC);

//     // foreach ($groups as &$group) {
//     //     // جلب الخيارات (Options) لكل مجموعة
//     //     $stmtOptions = $pdo->prepare("SELECT name, price, is_default FROM modifier_options WHERE group_id = ?");
//     //     $stmtOptions->execute([$group['id']]);
//     //     $options = $stmtOptions->fetchAll(PDO::FETCH_ASSOC);

//     //     // تحويل is_default و price لأرقام صحيحة للجافاسكريبت
//     //     foreach ($options as &$opt) {
//     //         $opt['checked'] = (bool) $opt['is_default'];
//     //         $opt['price'] = (float) $opt['price'];
//     //     }
//     //     $group['options'] = $options;
//     // }

//     $product['modifiers'] = $groups;
//     $product['price'] = (float) $product['price']; // تحويل السعر لرقم
// }

// إرجاع البيانات
echo json_encode([
    'categories' => $categories,
    'products' => $products
]);
?>