<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(403);
    echo json_encode(['error' => 'Non autorisé']);
    exit;
}


$visitorsFile = '../logs/visitors.json';
$visitors = [];
if (file_exists($visitorsFile)) {
    $visitors = json_decode(file_get_contents($visitorsFile), true);
}


$now = time();
$activeThreshold = 300;
$usersOnlineCount = 0;
foreach ($visitors as $visitor) {
    $visitorTimestamp = isset($visitor['timestamp']) ? strtotime($visitor['timestamp']) : 0;
    if ($visitorTimestamp && ($now - $visitorTimestamp) <= $activeThreshold) {
        $usersOnlineCount++;
    }
}


$mobileCount = 0;
foreach ($visitors as $visitor) {
    if (!empty($visitor['mobile']) && $visitor['mobile'] === true) {
        $mobileCount++;
    }
}


$robotsBlockedCount = 0;
foreach ($visitors as $visitor) {
    if (!empty($visitor['proxy']) && $visitor['proxy'] === true) {
        $robotsBlockedCount++;
    } elseif (!empty($visitor['bot']) && $visitor['bot'] === true) {
        $robotsBlockedCount++;
    }
}


$lastActivity = 0;
foreach ($visitors as $visitor) {
    $visitorTimestamp = isset($visitor['timestamp']) ? strtotime($visitor['timestamp']) : 0;
    if ($visitorTimestamp > $lastActivity) {
        $lastActivity = $visitorTimestamp;
    }
}
$lastActivityMinutesAgo = $lastActivity ? floor(($now - $lastActivity) / 60) : '-';


$data = [
    'usersOnlineCount' => $usersOnlineCount,
    'mobileCount' => $mobileCount,
    'robotsBlockedCount' => $robotsBlockedCount,
    'lastActivityMinutesAgo' => $lastActivityMinutesAgo,
];


header('Content-Type: application/json');
echo json_encode($data);
