<?php
require_once '../modules/load_env.php';

$bot_token = $_ENV['BOT_TOKEN'] ?? null;
$lastFile = __DIR__ . '/last_update_id.txt';
if (!file_exists($lastFile)) file_put_contents($lastFile, '0');
$lastUpdateId = (int)file_get_contents($lastFile);

function getUpdates($bot_token, $offset) {
    $url = "https://api.telegram.org/bot$bot_token/getUpdates?offset=$offset&timeout=2";
    return json_decode(file_get_contents($url), true);
}

$response = getUpdates($bot_token, $lastUpdateId + 1);
if (!$response || !$response['ok']) exit;

foreach ($response['result'] as $update) {
    $updateId = $update['update_id'];
    file_put_contents($lastFile, $updateId);

    // identifie l’utilisateur
    $chat_id = $update['message']['chat']['id'] ?? ($update['callback_query']['from']['id'] ?? null);
    if (!$chat_id) continue;

    // stocker par utilisateur
    $file = __DIR__ . "/updates_user_{$chat_id}.json";
    file_put_contents($file, json_encode($update));
}
