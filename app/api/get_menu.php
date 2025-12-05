<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Database configuration
$host = "localhost";
$db_name = "u574242705_menu";
$username = "u574242705_menu";
$password = "K*u@EDw9";

try {
    $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
    $conn->exec("set names utf8");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo json_encode(["error" => "Connection error: " . $exception->getMessage()]);
    exit();
}

// Fetch Categories
$categoriesQuery = "SELECT * FROM storeCategories WHERE enabled = '1' AND isHidden = '0' ORDER BY orderNo ASC";
$categoriesStmt = $conn->prepare($categoriesQuery);
$categoriesStmt->execute();
$categories = $categoriesStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Sections
$sectionsQuery = "SELECT * FROM storeSections WHERE enabled = '1' AND isHidden = '0' ORDER BY orderNo ASC";
$sectionsStmt = $conn->prepare($sectionsQuery);
$sectionsStmt->execute();
$sections = $sectionsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Nested Sections
$nestedSectionsQuery = "SELECT * FROM storeNestedSections WHERE enabled = '1' AND isHidden = '0' ORDER BY orderNo ASC";
$nestedSectionsStmt = $conn->prepare($nestedSectionsQuery);
$nestedSectionsStmt->execute();
$nestedSections = $nestedSectionsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Products
$productsQuery = "SELECT * FROM products WHERE enabled = '1' AND isHidden = '0' ORDER BY orderNo ASC";
$productsStmt = $conn->prepare($productsQuery);
$productsStmt->execute();
$products = $productsStmt->fetchAll(PDO::FETCH_ASSOC);

$folderStructure = '/uploads/images/products/cover/';

foreach ($products as &$product) {
    if (!empty($product['cover'])) {
        $product['cover'] = $folderStructure . $product['cover'];
    }
}

// Fetch Product Images
$imagesQuery = "SELECT * FROM productImages WHERE deletedAt IS NULL";
$imagesStmt = $conn->prepare($imagesQuery);
$imagesStmt->execute();
$productImages = $imagesStmt->fetchAll(PDO::FETCH_ASSOC);

$imagesFolder = '/uploads/images/products/images/';
foreach ($productImages as &$img) {
    if (!empty($img['image'])) {
        $img['image'] = $imagesFolder . $img['image'];
    }
}

// Fetch Product Options
$optionsQuery = "SELECT * FROM productOptions WHERE enabled = '1' AND isHidden = '0' ORDER BY orderNo ASC";
$optionsStmt = $conn->prepare($optionsQuery);
$optionsStmt->execute();
$productOptions = $optionsStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Product Addons
$addonsQuery = "SELECT * FROM productAddons WHERE enabled = '1' AND isHidden = '0' ORDER BY orderNo ASC";
$addonsStmt = $conn->prepare($addonsQuery);
$addonsStmt->execute();
$productAddons = $addonsStmt->fetchAll(PDO::FETCH_ASSOC);

// Construct Response
$response = [
    "storeCategories" => $categories,
    "storeSections" => $sections,
    "storeNestedSections" => $nestedSections,
    "products" => $products,
    "productImages" => $productImages,
    "productOptions" => $productOptions,
    "productAddons" => $productAddons
];

echo json_encode($response);
?>