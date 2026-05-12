<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbname = 'motorcycle_parts';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE 'estimated_delivery_date'");
    $exists = $stmt->rowCount() > 0;
    
    if (!$exists) {
        echo "Adding estimated_delivery_date column...\n";
        $pdo->exec("ALTER TABLE orders ADD COLUMN estimated_delivery_date DATE NULL");
        echo "✓ Column added successfully!\n";
    } else {
        echo "✓ Column already exists!\n";
    }
    
    // Add other missing columns
    $columns = ['delivery_status', 'actual_delivery_date', 'payment_status', 'bank_receipt', 'notes'];
    foreach ($columns as $column) {
        $stmt = $pdo->query("SHOW COLUMNS FROM orders LIKE '$column'");
        if ($stmt->rowCount() == 0) {
            echo "Adding $column column...\n";
            if ($column == 'delivery_status') {
                $pdo->exec("ALTER TABLE orders ADD COLUMN delivery_status VARCHAR(50) DEFAULT 'pending'");
            } elseif ($column == 'actual_delivery_date') {
                $pdo->exec("ALTER TABLE orders ADD COLUMN actual_delivery_date DATE NULL");
            } elseif ($column == 'payment_status') {
                $pdo->exec("ALTER TABLE orders ADD COLUMN payment_status VARCHAR(50) DEFAULT 'pending'");
            } elseif ($column == 'bank_receipt') {
                $pdo->exec("ALTER TABLE orders ADD COLUMN bank_receipt VARCHAR(255) NULL");
            } elseif ($column == 'notes') {
                $pdo->exec("ALTER TABLE orders ADD COLUMN notes TEXT NULL");
            }
            echo "✓ Added $column\n";
        }
    }
    
    echo "\n✅ All columns are now present!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}