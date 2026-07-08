<?php
/**
 * Système de logging d'erreurs pour le projet
 */

class ErrorLogger {
    private static $logFile = __DIR__ . '/error.log';
    
    public static function log($message, $level = 'INFO', $context = []) {
        $timestamp = date('Y-m-d H:i:s');
        $contextStr = !empty($context) ? ' | Context: ' . json_encode($context) : '';
        $logEntry = "[{$timestamp}] [{$level}] {$message}{$contextStr}\n";
        
        file_put_contents(self::$logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    public static function logError($message, $context = []) {
        self::log($message, 'ERROR', $context);
    }
    
    public static function logWarning($message, $context = []) {
        self::log($message, 'WARNING', $context);
    }
    
    public static function logInfo($message, $context = []) {
        self::log($message, 'INFO', $context);
    }
    
    public static function getLogs($lines = 100) {
        if (!file_exists(self::$logFile)) {
            return [];
        }
        
        $logs = file(self::$logFile, FILE_IGNORE_NEW_LINES);
        return array_slice(array_reverse($logs), 0, $lines);
    }
}

// Fonction globale pour faciliter l'utilisation
function logError($message, $context = []) {
    ErrorLogger::logError($message, $context);
}

function logWarning($message, $context = []) {
    ErrorLogger::logWarning($message, $context);
}

function logInfo($message, $context = []) {
    ErrorLogger::logInfo($message, $context);
}
?>
