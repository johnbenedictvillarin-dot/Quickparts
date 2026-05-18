<?php
$pdo = new PDO('mysql:host=yamanote.proxy.rlwy.net;port=15155;dbname=railway', 'root', 'SOZxFwJeTEbnZJaXPiepVmNvJOcDypNq');

// Clear all sessions
$pdo->exec('DELETE FROM sessions');
echo "Sessions cleared.\n";

// Check if table exists and has right structure
$stmt = $pdo->query("DESCRIBE sessions");
echo "\nSessions table structure:\n";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "  {$row['Field']} - {$row['Type']}\n";
}
