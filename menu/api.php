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
// ---------------------------------------------------------
// 2. جلب التصنيفات (Categories)
// ---------------------------------------------------------
// نفترض أن الجدول يحتوي على id و name (أو title)
$stmt = $pdo->query("SELECT * FROM storeNestedSections ORDER BY orderNo");
$rawCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories = [];
foreach ($rawCategories as $cat) {
    $categories[] = [
        'id' => $cat['id'],
        // ⚠️ تأكد أن اسم العمود في قاعدتك هو 'name' أو غيره إلى 'sectionName' مثلاً
        'name' => $cat['name'] ?? $cat['title'] ?? 'تصنيف',
    ];
}
// ---------------------------------------------------------
// 2. جلب التصنيفات (storeNestedSections)
// ---------------------------------------------------------
$stmt = $pdo->query("SELECT * FROM storeNestedSections ORDER BY orderNo ASC");
$rawCategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories = [];
foreach ($rawCategories as $cat) {
    $categories[] = [
        'id' => $cat['id'],
        // نتأكد من وجود الاسم، إذا كان العمود اسمه name أو title
        'name' => $cat['name'] ?? $cat['title'] ?? 'تصنيف بدون اسم',
    ];
}

// ---------------------------------------------------------
// 3. جلب المنتجات (products)
// ---------------------------------------------------------
// نستخدم الشروط: enabled = 1 و isHidden = 0 لعرض المنتجات المتاحة فقط
$stmt = $pdo->query("SELECT * FROM products WHERE enabled = 1 AND isHidden = 0 ORDER BY orderNo ASC");
$rawProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$finalProducts = [];

foreach ($rawProducts as $product) {
    $productId = $product['id'];

    // أ) معالجة الصورة (cover)
    // إذا كان الرابط كاملاً نتركه، وإذا كان مجرد اسم ملف نضيف المسار
    $imagePath = $product['cover'];
    if ($imagePath && !str_starts_with($imagePath, 'http')) {
        // ⚠️ عدل هذا الرابط حسب مكان تخزين الصور في سيرفرك
        $imagePath = 'https://your-domain.com/uploads/' . $imagePath;
    }
    // صورة افتراضية في حال عدم وجود غلاف
    $imageUrl = $imagePath ?: 'https://via.placeholder.com/300?text=No+Image';

    // ب) تجهيز الخيارات والإضافات
    $modifiers = [];
    $basePrice = 0; // سنحاول استخراجه من الخيارات

    // 1. جلب الخيارات (productOptions) - مثل الأحجام
    // نفترض أن العمود الرابط هو product_id أو productId
    $stmtOpt = $pdo->prepare("SELECT * FROM productOptions WHERE product_id = ?");
    $stmtOpt->execute([$productId]);
    $options = $stmtOpt->fetchAll(PDO::FETCH_ASSOC);

    if (count($options) > 0) {
        $formattedOptions = [];
        // تحديد السعر المبدئي للمنتج بناءً على أرخص خيار
        $prices = array_column($options, 'price');
        $basePrice = !empty($prices) ? min($prices) : 0;

        foreach ($options as $index => $opt) {
            $formattedOptions[] = [
                'name' => $opt['name'],
                'price' => (float) $opt['price'],
                // نجعل الخيار الأرخص هو الافتراضي
                'checked' => ((float) $opt['price'] === $basePrice)
            ];
        }

        $modifiers[] = [
            'title' => 'اختر النوع / الحجم',
            'type' => 'radio', // راديو لأن المستخدم يختار حجماً واحداً
            'options' => $formattedOptions
        ];
    }

    // 2. جلب الإضافات (productAddons) - مثل الجبن والصوص
    $stmtAddons = $pdo->prepare("SELECT * FROM productAddons WHERE product_id = ?");
    $stmtAddons->execute([$productId]);
    $addons = $stmtAddons->fetchAll(PDO::FETCH_ASSOC);

    if (count($addons) > 0) {
        $formattedAddons = [];
        foreach ($addons as $add) {
            $formattedAddons[] = [
                'name' => $add['name'],
                'price' => (float) $add['price'],
                'checked' => false
            ];
        }

        $modifiers[] = [
            'title' => 'الإضافات',
            'type' => 'checkbox', // تشيك بوكس لتعدد الاختيارات
            'options' => $formattedAddons
        ];
    }

    // ج) بناء كائن المنتج النهائي
    $finalProducts[] = [
        'id' => $product['id'],
        // الربط مع التصنيف عبر storeNestedSectionId
        'cat_id' => $product['storeNestedSectionId'],
        'name' => $product['name'],
        'description' => $product['description'] ?? '',
        // بما أن جدولك لا يحتوي على سعر، نأخذ السعر المحسوب من الخيارات
        'price' => (float) $basePrice,
        'image_url' => $imageUrl,
        'modifiers' => $modifiers
    ];
}

// ---------------------------------------------------------
// 4. إرسال الرد JSON
// ---------------------------------------------------------
echo json_encode([
    'categories' => $categories,
    'products' => $finalProducts
]);
?>