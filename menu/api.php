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
function safeQuery($pdo, $sql, $params = [])
{
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) {
        return [];
    }
}

$catId = isset($_GET['cat_id']) ? (int) $_GET['cat_id'] : 0;
$searchQuery = isset($_GET['search']) ? $_GET['search'] : null;

$response = [];

// 1. جلب الفئات (دائماً)
$response['categories'] = safeQuery($pdo, "SELECT * FROM storeNestedSections ORDER BY orderNo ASC");

// 2. منطق جلب المنتجات
$productsRaw = [];

if ($searchQuery) {
    // --- حالة البحث العام (Search All) ---
    // يبحث في الاسم أو الوصف
    $sql = "SELECT * FROM products WHERE (name LIKE ? OR description LIKE ?) AND enabled = 1 AND isHidden = 0";
    $searchTerm = "%" . $searchQuery . "%";
    $productsRaw = safeQuery($pdo, $sql, [$searchTerm, $searchTerm]);

} elseif ($catId > 0) {
    // --- حالة تصفح فئة محددة ---
    $productsRaw = safeQuery($pdo, "SELECT * FROM products WHERE storeNestedSectionId = ? AND enabled = 1 AND isHidden = 0 ORDER BY orderNo ASC", [$catId]);

} elseif ($catId == 0 && !empty($response['categories']) && !$searchQuery) {
    // --- الحالة الافتراضية (أول فئة) ---
    $catId = $response['categories'][0]['id'];
    $productsRaw = safeQuery($pdo, "SELECT * FROM products WHERE storeNestedSectionId = ? AND enabled = 1 AND isHidden = 0 ORDER BY orderNo ASC", [$catId]);
    $response['active_category'] = $catId;
}

// 3. معالجة المنتجات (إضافة الصور والخيارات)
$finalProducts = [];
foreach ($productsRaw as $product) {
    $pid = $product['id'];
    // لاحظ: قمت بإضافة مجلد cover لأننا حفظناها هناك في الكود السابق
    $img = $product['cover'];

    // جلب الخيارات
    $options = safeQuery($pdo, "SELECT * FROM productOptions WHERE productId = ? AND enabled = 1", [$pid]);
    $addons = safeQuery($pdo, "SELECT * FROM productAddons WHERE productId = ? AND enabled = 1", [$pid]);

    // حساب أقل سعر
    $basePrice = 0;
    $modifiers = [];

    if (!empty($options)) {
        $prices = array_column($options, 'price');
        $basePrice = !empty($prices) ? min($prices) : 0;

        $fmtOpts = [];
        foreach ($options as $o) {
            $fmtOpts[] = ['name' => $o['name'], 'price' => (float) $o['price']];
        }
        $modifiers[] = ['title' => 'اختر النوع', 'type' => 'radio', 'options' => $fmtOpts];
    }

    if (!empty($addons)) {
        $fmtAdds = [];
        foreach ($addons as $a) {
            $fmtAdds[] = ['name' => $a['name'], 'price' => (float) $a['price']];
        }
        $modifiers[] = ['title' => 'إضافات', 'type' => 'checkbox', 'options' => $fmtAdds];
    }

    $finalProducts[] = [
        'id' => $product['id'],
        'cat_id' => $product['storeNestedSectionId'],
        'name' => $product['name'],
        'description' => $product['description'] ?? '',
        'price' => (float) $basePrice,
        'image_url' => $img,
        'modifiers' => $modifiers
    ];
}

$response['products'] = $finalProducts;
echo json_encode($response);
?>