<?php
/**
 * Système de validation des sessions pour éviter les mélanges
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function validateUserSession($uid) {
    // Vérifier que l'UID de la session correspond à celui demandé
    $sessionUid = $_SESSION['user_id'] ?? null;
    
    if ($sessionUid !== $uid) {
        // Log de sécurité
        $logFile = __DIR__ . '/security.log';
        $logEntry = date('Y-m-d H:i:s') . " - TENTATIVE DE FRAUDE: UID session ($sessionUid) != UID demandé ($uid) - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
        
        return false;
    }
    
    return true;
}

function generateSecureUid() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $timestamp = microtime(true);
    $random = bin2hex(random_bytes(8));
    return 'user_' . substr(md5($ip . $timestamp . $random), 0, 20);
}

function cleanupOldSessions() {
    $actionsDir = __DIR__;
    $maxAge = 1800; // 30 minutes
    
    // Nettoyer les fichiers de session trop anciens
    foreach (glob($actionsDir . "/code_pin_*.txt") as $file) {
        if (filemtime($file) < (time() - $maxAge)) {
            unlink($file);
        }
    }
    
    foreach (glob($actionsDir . "/pending_uid_*.txt") as $file) {
        if (filemtime($file) < (time() - $maxAge)) {
            unlink($file);
        }
    }
}
?>
