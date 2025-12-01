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
// 3. جلب المنتجات ودمج الصور والخيارات
// ---------------------------------------------------------
$stmt = $pdo->query("SELECT * FROM products");
$rawProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

$finalProducts = [];

foreach ($rawProducts as $product) {
    $productId = $product['id'];

    // أ) جلب الصورة (نأخذ أول صورة فقط للعرض)
    $stmtImg = $pdo->prepare("SELECT * FROM productImages WHERE product_id = ? LIMIT 1");
    $stmtImg->execute([$productId]);
    $imgData = $stmtImg->fetch(PDO::FETCH_ASSOC);
    // ⚠️ تأكد من اسم عمود الرابط (url أو image_path)
    $imageUrl = $imgData['url'] ?? $imgData['image'] ?? 'placeholder.jpg';

    // ب) تجهيز مصفوفة الخيارات المدمجة (Modifiers)
    $modifiers = [];

    // 1. جلب الخيارات الأساسية (productOptions) -> نعاملها كـ Radio
    $stmtOpt = $pdo->prepare("SELECT * FROM productOptions WHERE product_id = ?");
    $stmtOpt->execute([$productId]);
    $options = $stmtOpt->fetchAll(PDO::FETCH_ASSOC);

    if (count($options) > 0) {
        // تحويل شكل البيانات لما يفهمه الجافاسكريبت
        $formattedOptions = [];
        foreach ($options as $opt) {
            $formattedOptions[] = [
                // ⚠️ تأكد من أسماء الأعمدة (name, price)
                'name' => $opt['name'],
                'price' => (float) $opt['price'],
                'checked' => false // أو ضع منطق لتحديد الافتراضي
            ];
        }

        // إضافة المجموعة لمصفوفة Modifiers
        $modifiers[] = [
            'title' => 'اختر النوع / الحجم', // عنوان ثابت أو من القاعدة
            'type' => 'radio',
            'options' => $formattedOptions
        ];
    }

    // 2. جلب الإضافات (productAddons) -> نعاملها كـ Checkbox
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
            'title' => 'إضافات',
            'type' => 'checkbox',
            'options' => $formattedAddons
        ];
    }

    // ج) بناء كائن المنتج النهائي
    $finalProducts[] = [
        'id' => $product['id'],
        // ⚠️ تأكد أن اسم العمود الذي يربط المنتج بالقسم هو section_id أو cat_id
        'cat_id' => $product['section_id'] ?? $product['category_id'],
        'name' => $product['name'],
        'description' => $product['description'] ?? '',
        'price' => (float) $product['price'],
        'image_url' => $imageUrl,
        'modifiers' => $modifiers // المصفوفة السحرية التي تشغل المودال
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