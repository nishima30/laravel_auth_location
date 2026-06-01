<?php

try {
    require __DIR__ . '/../public/index.php';
} catch (Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain');

    echo "ERROR CLASS: " . get_class($e) . "\n";
    echo "ERROR MESSAGE: " . $e->getMessage() . "\n";
    echo "ERROR FILE: " . $e->getFile() . "\n";
    echo "ERROR LINE: " . $e->getLine() . "\n\n";
    echo $e->getTraceAsString();
}