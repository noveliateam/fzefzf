<?//Fonctions refresh de mes stats

$visitors = [];
$visitorsFile = './logs/visitors.json';
if (file_exists($visitorsFile)) {
    $jsonContent = file_get_contents($visitorsFile);
    $visitors = json_decode($jsonContent, true);
}

// robots bloqués
$bannedVisits = [];
$bannedVisitsFile = './logs/banned_visits.txt';
if (file_exists($bannedVisitsFile)) {
    $bannedJsonContent = file_get_contents($bannedVisitsFile);
    $bannedVisits = json_decode($bannedJsonContent, true);
}

// Calcule users en ligne 1 minutes max
$now = time();
$activeThreshold = 60; // Tps en secondes
$usersOnlineCount = 0;
foreach ($visitors as $visitor) {
    $visitorTimestamp = isset($visitor['timestamp']) ? strtotime($visitor['timestamp']) : 0;
    if ($visitorTimestamp && ($now - $visitorTimestamp) <= $activeThreshold) {
        $usersOnlineCount++;
    }
}

// Comptage appareils mobiles
$mobileCount = 0;
foreach ($visitors as $visitor) {
    if (!empty($visitor['mobile']) && $visitor['mobile'] === true) {
        $mobileCount++;
    }
}


$robotsBlockedCount = is_array($bannedVisits) ? count($bannedVisits) : 0;


$lastActivity = 0;
foreach ($visitors as $visitor) {
    $visitorTimestamp = isset($visitor['timestamp']) ? strtotime($visitor['timestamp']) : 0;
    if ($visitorTimestamp > $lastActivity) {
        $lastActivity = $visitorTimestamp;
    }
}
$lastActivityMinutesAgo = $lastActivity ? floor(($now - $lastActivity) / 60) : '-';

foreach ($visitors as &$visitor) {
    if (!empty($visitor['countryCode'])) {
        $visitor['country_flag'] = "https://flagcdn.com/h20/" . strtolower($visitor['countryCode']) . ".png";
    } else {
        $visitor['country_flag'] = null;
    }
}
unset($visitor);

?>