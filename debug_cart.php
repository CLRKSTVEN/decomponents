<?php
// Debug script to inspect cart and orders
define('BASEPATH', '');
session_start();

echo "<!DOCTYPE html>";
echo "<html><head><title>Cart & Orders Debug</title>";
echo "<style>body { font-family: monospace; padding: 20px; } pre { background: #f0f0f0; padding: 10px; border-radius: 5px; }</style>";
echo "</head><body>";

echo "<h1>🔍 Cart & Orders Debug Report</h1>";

// 1. Session cart
echo "<h2>📦 Session Cart (ez_cart)</h2>";
echo "<pre>";
if (isset($_SESSION['ez_cart'])) {
    $cart = $_SESSION['ez_cart'];
    echo "Type: " . gettype($cart) . "\n";
    echo "Count: " . (is_array($cart) ? count($cart) : 'N/A') . "\n";
    echo "Contents:\n";
    var_dump($cart);
} else {
    echo "NOT SET in session\n";
}
echo "</pre>";

// 2. Calculate count like navbar does
echo "<h2>📊 Cart Count (New Method)</h2>";
echo "<pre>";
$cartCount = 0;
$cart = $_SESSION['ez_cart'] ?? [];
if (is_array($cart) && count($cart) > 0) {
    foreach ($cart as $item) {
        if (is_array($item) && isset($item['qty'])) {
            $cartCount += (int)$item['qty'];
        } elseif (is_array($item) && isset($item['quantity'])) {
            $cartCount += (int)$item['quantity'];
        }
    }
}
echo "Total cart count: " . $cartCount . "\n";
echo "</pre>";

// 3. Database info
echo "<h2>🗄️ Database Orders Table</h2>";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=decomponents', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if table exists
    $stmt = $pdo->query("SHOW TABLES LIKE 'orders'");
    if ($stmt->rowCount() === 0) {
        echo "Table 'orders' does NOT exist\n";
    } else {
        echo "Table 'orders' EXISTS\n\n";

        // Table structure
        echo "<b>Table Structure:</b>\n";
        echo "<pre>";
        $stmt = $pdo->query("DESCRIBE `orders`");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $col) {
            echo $col['Field'] . " (" . $col['Type'] . ")\n";
        }
        echo "</pre>";

        // Row count
        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM `orders`");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<b>Total Rows: " . $result['cnt'] . "</b>\n\n";

        // Show recent records
        if ($result['cnt'] > 0) {
            echo "<b>Recent Orders (Last 10):</b>\n";
            echo "<pre>";
            $stmt = $pdo->query("SELECT id, user_id, full_name, quantity, total_amount, status, created_at FROM `orders` ORDER BY id DESC LIMIT 10");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                echo "ID: {$row['id']} | User: {$row['user_id']} | Qty: {$row['quantity']} | Amount: {$row['total_amount']} | Status: {$row['status']}\n";
            }
            echo "</pre>";
        }
    }
} catch (Exception $e) {
    echo "Error connecting to database: " . $e->getMessage() . "\n";
}

// 4. Order items table
echo "<h2>📋 Database Order Items Table</h2>";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=decomponents', 'root', '');

    $stmt = $pdo->query("SHOW TABLES LIKE 'order_items'");
    if ($stmt->rowCount() === 0) {
        echo "Table 'order_items' does NOT exist\n";
    } else {
        echo "Table 'order_items' EXISTS\n\n";

        $stmt = $pdo->query("SELECT COUNT(*) as cnt FROM `order_items`");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<b>Total Rows: " . $result['cnt'] . "</b>\n\n";

        if ($result['cnt'] > 0) {
            echo "<b>Recent Items (Last 10):</b>\n";
            echo "<pre>";
            $stmt = $pdo->query("SELECT * FROM `order_items` ORDER BY id DESC LIMIT 10");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                echo "Item: {$row['id']} | Order: {$row['order_id']} | Product: {$row['product_id']} | Qty: {$row['quantity']}\n";
            }
            echo "</pre>";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo "</body></html>";
