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
}// دالة مساعدة للاستعلام الآمن
function safeQuery($pdo, $sql, $params = []) {
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Exception $e) { return []; }
}

// استقبال رقم الفئة من الرابط (مثال: api.php?cat_id=5)
$catId = isset($_GET['cat_id']) ? (int)$_GET['cat_id'] : 0;

$response = [];

// ---------------------------------------------------------
// 1. جلب قائمة الفئات (دائماً نرسلها لكي يرسم الشريط العلوي)
// ---------------------------------------------------------
$categoriesRaw = safeQuery($pdo, "SELECT * FROM storeNestedSections ORDER BY orderNo ASC");
$categories = [];
foreach ($categoriesRaw as $cat) {
    $categories[] = [
        'id' => $cat['id'],
        'name' => $cat['name'] ?? $cat['title'] ?? 'تصنيف',
    ];
}
$response['categories'] = $categories;

// ---------------------------------------------------------
// 2. جلب المنتجات (فقط إذا تم تحديد الفئة، أو لجلب الفئة الأولى تلقائياً)
// ---------------------------------------------------------

// إذا لم يحدد المستخدم فئة، نختار أول فئة تلقائياً لجلب منتجاتها
if ($catId == 0 && !empty($categories)) {
    $catId = $categories[0]['id'];
}

$response['active_category'] = $catId; // نخبر الفرونت اند بالفئة الحالية

if ($catId > 0) {
    // جلب المنتجات التابعة لهذه الفئة فقط
    // نستخدم storeNestedSectionId للربط حسب هيكلة جدولك
    $productsRaw = safeQuery($pdo, "SELECT * FROM products WHERE storeNestedSectionId = ? AND enabled = 1 AND isHidden = 0 ORDER BY orderNo ASC", [$catId]);
    
    $finalProducts = [];

    foreach ($productsRaw as $product) {
        $pid = $product['id'];
        
        // معالجة الصورة (من جدول products عمود cover)
        $img = $product['cover'] ? 'https://your-domain.com/uploads/'.$product['cover'] : 'https://via.placeholder.com/300';

        // جلب الخيارات من الجدول الجديد productOptions
        // لاحظ: العمود الرابط هو productId
        $options = safeQuery($pdo, "SELECT * FROM productOptions WHERE productId = ? AND enabled = 1", [$pid]);
        
        // جلب الإضافات من الجدول الجديد productAddons
        $addons = safeQuery($pdo, "SELECT * FROM productAddons WHERE productId = ? AND enabled = 1", [$pid]);

        $basePrice = 0;
        $modifiers = [];

        // معالجة الخيارات (الأحجام/الأنواع)
        if (!empty($options)) {
            $prices = array_column($options, 'price');
            $basePrice = !empty($prices) ? min($prices) : 0;
            
            $fmtOpts = [];
            foreach($options as $o) {
                // نستخدم name و price من الجدول الجديد
                $fmtOpts[] = [
                    'name' => $o['name'], 
                    'price' => (float)$o['price'],
                    'checked' => ((float)$o['price'] == $basePrice)
                ];
            }
            $modifiers[] = ['title'=>'اختر النوع', 'type'=>'radio', 'options'=>$fmtOpts];
        } else {
            // إذا لم يكن هناك خيارات، قد يكون السعر 0، هذا يعتمد على بياناتك
            $basePrice = 0; 
        }

        // معالجة الإضافات
        if (!empty($addons)) {
            $fmtAdds = [];
            foreach($addons as $a) {
                $fmtAdds[] = [
                    'name' => $a['name'], 
                    'price' => (float)$a['price'],
                    'checked' => false
                ];
            }
            $modifiers[] = ['title'=>'إضافات', 'type'=>'checkbox', 'options'=>$fmtAdds];
        }

        $finalProducts[] = [
            'id' => $product['id'],
            'cat_id' => $product['storeNestedSectionId'],
            'name' => $product['name'],
            'description' => $product['description'] ?? '',
            'price' => (float)$basePrice,
            'image_url' => $img,
            'modifiers' => $modifiers
        ];
    }
    
    $response['products'] = $finalProducts;
} else {
    $response['products'] = [];
}

echo json_encode($response);
?>