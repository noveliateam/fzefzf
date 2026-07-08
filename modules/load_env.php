<?php
// loadenv.php

// Pour l'utiliser : 
//require_once 'loadenv.php'; // Charge les variables .env automatiquement
// Maintenant tu peux accéder à tes variables d'environnement :
//$adminLogin = $_ENV['ADMIN_LOGIN'] ?? null;
//$adminPassword = $_ENV['ADMIN_PASSWORD'] ?? null;
//$botToken = $_ENV['BOT_TOKEN'] ?? null;
//$chatId = $_ENV['CHAT_ID'] ?? null;



function loadEnv(string $path = __DIR__ . '/../.env'): void {
    if (!file_exists($path)) {
        throw new Exception("Fichier .env introuvable à l'emplacement : $path");
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
      
        if (strpos(trim($line), '#') === 0) continue;

        if (preg_match('/^\s*([\w\.]+)\s*=\s*(.*)\s*$/', $line, $matches)) {
            $key = $matches[1];
            $value = trim($matches[2]);
            
            if ((str_starts_with($value, '"') && str_ends_with($value, '"')) ||
                (str_starts_with($value, "'") && str_ends_with($value, "'"))) {
                $value = substr($value, 1, -1);
            }
           
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}


try {
    loadEnv();
} catch (Exception $e) {
    die("⛔ Erreur lors du chargement du fichier .env : " . $e->getMessage());
}
