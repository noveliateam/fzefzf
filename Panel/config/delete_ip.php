<?php
$file = 'whitelist.txt';
$ipToDelete = trim($_POST['ip'] ?? '');

if (!$ipToDelete) {
    echo "Aucune IP fournie.";
    exit;
}

if (!file_exists($file)) {
    echo "Fichier introuvable.";
    exit;
}

$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$updated = array_filter($lines, fn($line) => trim($line) !== $ipToDelete);

if (count($updated) === count($lines)) {
    echo "IP non trouvée.";
    exit;
}

file_put_contents($file, implode(PHP_EOL, $updated) . PHP_EOL);
echo "IP supprimée avec succès.";
