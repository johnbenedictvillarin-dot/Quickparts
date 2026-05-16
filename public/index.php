<?php
// Simple responder for Railway
http_response_code(200);
header('Content-Type: text/html');
echo "<h1>QuickParts Server is Running</h1>";
echo "<p>Time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>Port: " . (getenv('PORT') ?: '8080') . "</p>";