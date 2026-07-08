<?php
header('Content-Type: application/json');

$visitorsFile = __DIR__ . '/../logs/visitors.json';
$events = [];
$now = time();
$recentDelay = 120; // secondes (2 minutes)

if (file_exists($visitorsFile)) {
    $visitors = json_decode(file_get_contents($visitorsFile), true);
    if (is_array($visitors)) {
        foreach ($visitors as $v) {
            $ts = isset($v['timestamp']) ? strtotime($v['timestamp']) : 0;
            if ($ts && ($now - $ts) <= $recentDelay) {
                // Nouvelle connexion
                $ip = $v['ip'] ?? '';
                $isBot = !empty($v['bot']) || !empty($v['proxy']);
                $country = $v['country'] ?? '';
                $isp = $v['isp'] ?? '';
                $msg = $isBot
                    ? "Bot ou proxy détecté : $ip" . ($isp ? " ($isp)" : "")
                    : "Nouvelle connexion : $ip" . ($country ? " ($country)" : "");
                $type = $isBot ? 'error' : 'success';
                $events[] = [
                    'id' => md5(($isBot ? 'bot-' : 'co-') . $ip . '-' . $ts),
                    'type' => $type,
                    'message' => $msg
                ];
            }
        }
    }
}
echo json_encode($events); 