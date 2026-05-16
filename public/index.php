<?php
http_response_code(200);
header('Content-Type: text/plain');
echo "QuickParts OK - " . date('Y-m-d H:i:s') . " - PORT=" . (getenv('PORT') ?? 'not-set');
