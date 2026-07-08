<?php
declare(strict_types=1);

// 🔧 Configuration des chemins
define('BOT_JSON_FILE', __DIR__ . '/list_bots.json');
define('LOG_FILE', __DIR__ . '/bot-blocked.log');
define('WHITELIST_FILE', __DIR__ . '/../Panel/config/whitelist.txt'); // ⚠️ adapte le chemin si besoin

// 🛡️ Récupère l'IP utilisateur
if (!function_exists('getUserIP')) {
function getUserIP(): string {
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
        if (!empty($_SERVER[$key])) {
            $ipList = explode(',', (string)$_SERVER[$key]);
            return trim(reset($ipList));
        }
    }
    return 'UNKNOWN';
}
}

// 🚫 Fonction de blocage
function blockAccess(string $reason, string $ip, string $ua): void {
    $log = sprintf("[%s] IP: %s | Reason: %s | UA: %s\n", date('Y-m-d H:i:s'), $ip, $reason, $ua);
    file_put_contents(LOG_FILE, $log, FILE_APPEND | LOCK_EX);

    header('HTTP/1.1 403 Forbidden');
    exit("403 - Bot détecté");
}

// 🧪 Vérifie la whitelist
$ip = getUserIP();
$whitelist = file_exists(WHITELIST_FILE)
    ? array_map('trim', file(WHITELIST_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES))
    : [];

if (in_array($ip, $whitelist, true)) {
    return; // ✅ Sortie immédiate pour IP whitelistée
}

// 🔍 Analyse du User-Agent
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
if (empty($userAgent)) {
    blockAccess("User-Agent vide", $ip, 'empty');
}

// 📦 Chargement de la liste des bots
if (!file_exists(BOT_JSON_FILE)) {
    error_log("❌ Fichier bot JSON introuvable.");
    return;
}

$botsList = json_decode(file_get_contents(BOT_JSON_FILE), true);
if (!is_array($botsList)) {
    error_log("❌ Erreur de parsing JSON dans BOT_JSON_FILE.");
    return;
}

// 🔍 Détection par motif
foreach ($botsList as $bot) {
    if (!isset($bot['pattern'])) continue;

    $pattern = $bot['pattern'];
    if (@preg_match('/' . $pattern . '/i', $userAgent)) {
        blockAccess("Détecté par pattern : $pattern", $ip, $userAgent);
    }
}
