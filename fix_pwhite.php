<?php
require 'includes/db.php';

echo "<h2>Fixing P-White Image...</h2>";

try {
    // 1. Find products named P-White or Platinum White
    $stmt = $pdo->prepare("SELECT id FROM products WHERE name LIKE '%P-White%' OR name LIKE '%Platinum White%' LIMIT 1");
    $stmt->execute();
    $product = $stmt->fetch();

    if ($product) {
        $productId = $product->id;
        echo "Found Product ID: $productId <br>";

        // 2. Check current images
        $imgStmt = $pdo->prepare("SELECT id, url FROM product_images WHERE product_id = ?");
        $imgStmt->execute([$productId]);
        $image = $imgStmt->fetch();

        $newUrl = 'assets/images/products/p-white.png';

        if ($image) {
            // Update existing image record
            $update = $pdo->prepare("UPDATE product_images SET url = ? WHERE id = ?");
            $update->execute([$newUrl, $image->id]);
            echo "Updated image record ID {$image->id} to $newUrl <br>";
        } else {
            // Insert new image record
            $insert = $pdo->prepare("INSERT INTO product_images (product_id, url, is_main) VALUES (?, ?, 1)");
            $insert->execute([$productId, $newUrl]);
            echo "Inserted new image record for Product $productId <br>";
        }
    } else {
        echo "No product found with 'P-White' in the name. Please check your database for the exact product name.";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
