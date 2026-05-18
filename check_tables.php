<?php
$pdo = new PDO('mysql:host=yamanote.proxy.rlwy.net;port=15155;dbname=railway', 'root', 'SOZxFwJeTEbnZJaXPiepVmNvJOcDypNq');
$stmt = $pdo->query('SHOW TABLES');
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
foreach ($tables as $t) echo "$t\n";

// Check if sessions table has data
if (in_array('sessions', $tables)) {
    $count = $pdo->query('SELECT COUNT(*) FROM sessions')->fetchColumn();
    echo "\nsessions table has $count rows\n";
} else {
    echo "\nWARNING: sessions table does NOT exist!\n";
}
