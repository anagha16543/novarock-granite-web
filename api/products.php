<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
require_once '../includes/db.php';

try {
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
    $category = isset($_GET['category']) ? $_GET['category'] : null;

    $query = "SELECT p.*, c.name as category_name 
              FROM products p 
              LEFT JOIN categories c ON p.category_id = c.id";
    
    $params = [];
    if ($category) {
        $query .= " WHERE c.slug = ?";
        $params[] = $category;
    }
    
    $query .= " ORDER BY p.created_at DESC LIMIT ?";
    $params[] = $limit;

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $products = $stmt->fetchAll();

    // Map images for each product (Simplified for now)
    foreach ($products as &$product) {
        $imgStmt = $pdo->prepare("SELECT url, is_main FROM product_images WHERE product_id = ?");
        $imgStmt->execute([$product->id]);
        $images = $imgStmt->fetchAll();
        $product->images = array_map(function($img) {
            return ['url' => $img->url, 'is_main' => (bool)$img->is_main];
        }, $images);
        
        // Ensure specifications are consistent with Next.js expectations
        $product->specifications = [
            'origin' => $product->origin ?? 'Premium Import',
            'finish' => $product->finish ?? 'Standard',
            'thickness' => $product->thickness ?? '18mm'
        ];
    }

    echo json_encode([
        'products' => $products,
        'status' => 'success'
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Internal Server Error',
        'message' => $e->getMessage()
    ]);
}
