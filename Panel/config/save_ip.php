<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ip'])) {
    $ip = trim($_POST['ip']);

    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        echo "IP invalide.";
        exit;
    }

    $file = __DIR__ . '/whitelist.txt';

    // Lire les IPs existantes
    $ips = file_exists($file) ? file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];

    // Éviter les doublons
    if (!in_array($ip, $ips)) {
        file_put_contents($file, $ip . PHP_EOL, FILE_APPEND | LOCK_EX);
        echo "IP enregistrée avec succès.";
    } else {
        echo "IP déjà enregistrée.";
    }
    exit;
}
echo "Requête invalide.";
