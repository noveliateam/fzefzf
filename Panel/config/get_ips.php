<?php
$file = __DIR__ . '/whitelist.txt';

if (!file_exists($file)) {
    echo json_encode([]);
    exit;
}

$ips = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$ips = array_unique($ips);

header('Content-Type: application/json');
echo json_encode($ips);
