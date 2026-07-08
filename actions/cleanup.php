<?php
/**
 * Script de nettoyage automatique des fichiers temporaires
 * À exécuter périodiquement via cron ou appel direct
 */

$actionsDir = __DIR__;
$maxAge = 300; // 5 minutes en secondes
$cleaned = 0;

// Nettoyer les fichiers pending orphelins
foreach (glob($actionsDir . "/pending_uid_*.txt") as $file) {
    if (filemtime($file) < (time() - $maxAge)) {
        unlink($file);
        $cleaned++;
    }
}

// Nettoyer les fichiers PIN orphelins
foreach (glob($actionsDir . "/code_pin_*.txt") as $file) {
    if (filemtime($file) < (time() - $maxAge)) {
        unlink($file);
        $cleaned++;
    }
}

// Nettoyer les fichiers de mapping IP-UID orphelins
$ipToUidDir = $actionsDir . "/../ip_to_uid";
if (is_dir($ipToUidDir)) {
    foreach (glob($ipToUidDir . "/*.txt") as $file) {
        if (filemtime($file) < (time() - $maxAge)) {
            unlink($file);
            $cleaned++;
        }
    }
}

// Log du nettoyage
$logFile = $actionsDir . "/cleanup.log";
$logEntry = date('Y-m-d H:i:s') . " - Nettoyage effectué: $cleaned fichiers supprimés\n";
file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);

echo "Nettoyage terminé: $cleaned fichiers supprimés\n";
?>
