<?php

const BLOCK_REDIRECT = '../pages/ban.php';

$blockedIps = [
    // AWS (Amazon Web Services) - régions principales
    '13.52.*.*',       // US West (Oregon)
    '13.59.*.*',       // EU (Frankfurt)
    '13.224.*.*',      // US East (N. Virginia)
    '13.235.*.*',      // Asia Pacific (Mumbai)
    '18.196.*.*',      // US East (Ohio)
    '3.210.*.*',       // Asia Pacific (Tokyo)
    '52.95.*.*',       // US East (Ohio)

    // Microsoft Azure - régions clés
    '20.36.*.*',       // US East
    '20.50.*.*',       // US West
    '52.94.*.*',       // East US 2
    '40.112.*.*',      // West Europe
    '52.176.*.*',      // South Central US

    // Google Cloud Platform - plages larges
    '35.192.*.*',
    '35.196.*.*',
    '35.199.*.*',
    '130.211.*.*',
    '104.154.*.*',
    '104.198.*.*',

    // OVH
    '51.15.*.*',
    '51.158.*.*',
    '163.172.*.*',

    // Hetzner
    '138.201.*.*',
    '78.47.*.*',
    '95.216.*.*',

    // DigitalOcean
    '159.89.*.*',
    '178.62.*.*',
    '64.227.*.*',
    '46.101.*.*',
    '209.97.*.*',
    '104.236.*.*',

    // Vultr
    '198.211.*.*',
    '198.199.*.*',
    '104.248.*.*',
    '172.105.*.*',

    // Linode
    '139.162.*.*',
    '50.116.*.*',

    // Scaleway
    '51.91.*.*',
    '51.158.*.*',

    // IPs uniques suspectes (proxy, bots, scanners)
    '173.239.240.147',  // Proxy suspect
    '103.248.172.42',   // Proxy suspect
    '47.30.133.89',     // VPN / Proxy
    '185.191.171.17',   // Botnet
    '185.191.171.26',   // Botnet
    '185.191.171.38',   // Botnet
    '185.191.171.44',   // Botnet
    '185.191.171.45',   // Botnet
    '103.216.202.11',   // Proxy suspect
    '185.62.189.146',   // Botnet connu
    '194.67.37.90',     // Proxy / scanner connu
];



$blockedUserAgents = [
    // Mots-clés génériques
    'bot', 'spider', 'crawler', 'curl', 'wget', 'python', 'java', 'php',
    'go-http-client', 'libwww', 'scan', 'checker', 'masscan', 'acunetix',
    'netsparker', 'sqlmap', 'ahrefs', 'semrush', 'mj12bot', 'bingbot',
    'googlebot', 'yandex', 'baiduspider', 'facebookexternalhit',
    'discordbot', 'telegrambot', 'scrapy', 'node-fetch', 'axios',
    'headless', 'lighthouse', 'pagespeed', 'zgrab', 'shodan', 'censys',
    'whatweb', 'wpscan', 'dirbuster', 'nikto', 'uptime', 'pingdom',
    'cybercrimetracker', 'netcraft', 'grequests', 'java/', 'go/',   
    'httpclient', 'okhttp', 'python-requests', 'fetch/', 'httpie',  
    'morfeus', 'masscan', 'sqlninja', 'fimap', 'nmap', 'acunetix',
    'nessus', 'fiddler', 'httpdebug', 'fuzzer', 'webinspect', 'dirbuster',
    
    'applebot', 'embedly', 'flipboard', 'linkedinbot', 'outbrain', 'pinterest', 'quora link preview',
];


$blockedHostnames = [
    // Bots, crawlers, spiders, scanners
    "bot", "crawler", "spider", "scanner", "proxy", "vpn", "anonymizer", "tor-exit", "tor-node", "tor-relay",
    "scanner", "sqlmap", "nikto", "nessus", "acunetix", "netsparker", "wpscan", "nmap", "masscan", "zaproxy", "burp",

    // Services Cloud et fournisseurs d’hébergement souvent utilisés par bots ou proxys
    "amazonaws", "aws", "azure", "google-cloud", "cloudflare", "digitalocean", "linode", "vultr", "ovh", "hetzner",
    "scaleway", "rackspace", "dreamhost", "fastly", "cdn77", "cdn77.net", "akamai", "maxcdn",

    // Réseaux anonymes et VPN connus
    "hide.me", "hidemyass", "privoxy", "privatelayer", "expressvpn", "nordvpn", "surfshark", "cyberghost", "protonvpn",
    "windscribe", "ipvanish", "purevpn", "torguard", "vpnbook",

    // Bots SEO et scraping agressifs
    "ahrefs", "semrush", "majestic", "mj12bot", "seznambot", "dotbot", "sistrix", "blexbot", "bingbot", "yandex", "baiduspider",
    "facebot", "facebookexternalhit", "twitterbot", "linkedinbot",

    // Messageries et bots réseaux sociaux douteux
    "telegrambot", "discordbot", "slackbot", "whatsapp", "linebot", "vkshare",

    // Proxies & anonymisateurs divers
    "proxy", "proxifier", "sockproxy", "socks", "transparentproxy", "openproxy", "anonymousproxy", "vpnproxy",

    // Serveurs mail & spam suspects (optionnel)
    "smtp", "mail", "mx.", "email.", "postfix", "exim", "sendmail",

    // Hébergeurs et services associés potentiellement abusés
    "herokuapp", "fly.io", "render.com", "zeit.co", "vercel.app",

    // Termes divers liés à la sécurité offensive
    "exploit", "hack", "attack", "brute", "dos", "ddos", "flood", "intrusion", "scanner",

    // Miscellaneous potentiellement malveillants
    "botnet", "crawler", "spambot", "scraper", "spammer", "malware", "ransomware"
];


const CACHE_FILE = __DIR__ . '/dns_cache.json';
const CACHE_TTL = 86400;

// 📌 Définir WHITELIST_FILE si pas encore défini
if (!defined('WHITELIST_FILE')) {
    define('WHITELIST_FILE', __DIR__ . '/whitelist.txt'); // <-- adapte ce chemin si nécessaire
}

function loadDnsCache(): array
{
    if (!file_exists(CACHE_FILE)) return [];
    $content = file_get_contents(CACHE_FILE);
    $data = json_decode($content, true);
    return is_array($data) ? $data : [];
}

function saveDnsCache(array $cache): void
{
    file_put_contents(CACHE_FILE, json_encode($cache, JSON_PRETTY_PRINT));
}

function getCachedHostByAddr(string $ip): string
{
    static $cache = null;
    if ($cache === null) $cache = loadDnsCache();

    if (isset($cache[$ip]) && time() - $cache[$ip]['timestamp'] < CACHE_TTL) {
        return $cache[$ip]['hostname'];
    }

    $hostname = strtolower(gethostbyaddr($ip));
    $cache[$ip] = [
        'hostname' => $hostname,
        'timestamp' => time(),
    ];
    saveDnsCache($cache);

    return $hostname;
}

function wildcardToRegex(string $pattern): string
{
    $regex = str_replace(['.', '*'], ['\.', '.*'], $pattern);
    return '/^' . $regex . '$/i';
}

function isBlockedIp(string $ip, array $patterns): bool
{
    foreach ($patterns as $pattern) {
        if ($ip === $pattern) return true;
        if (strpos($pattern, '*') !== false && preg_match(wildcardToRegex($pattern), $ip)) return true;
    }
    return false;
}

function isBlockedUserAgent(string $ua, array $keywords): bool
{
    $ua = strtolower(trim($ua));
    if ($ua === '') return true;
    foreach ($keywords as $keyword) {
        if ($keyword !== '' && strpos($ua, strtolower($keyword)) !== false) return true;
    }
    return false;
}

function isBlockedHostname(string $ip, array $keywords): bool
{
    $hostname = getCachedHostByAddr($ip);
    foreach ($keywords as $keyword) {
        if ($keyword !== '' && strpos($hostname, strtolower($keyword)) !== false) return true;
    }
    return false;
}

function isIpWhitelisted(string $ip, array $whitelist): bool
{
    foreach ($whitelist as $pattern) {
        if ($pattern === $ip) return true;
        if (strpos($pattern, '*') !== false && preg_match(wildcardToRegex($pattern), $ip)) return true;
    }
    return false;
}

// 🔐 IP et User-Agent du visiteur
$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

// 📂 Chargement whitelist IP
$allowedIPs = file_exists(WHITELIST_FILE)
    ? array_map('trim', file(WHITELIST_FILE, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES))
    : [];


// ✅ Skip tous les contrôles si IP whitelistée
if (isIpWhitelisted($ip, $allowedIPs)) {
    return;
}

// 🧱 Vérification anti-bot
if (
    isBlockedIp($ip, $blockedIps) ||
    isBlockedUserAgent($ua, $blockedUserAgents) ||
    isBlockedHostname($ip, $blockedHostnames)
) {
    if (defined('BLOCK_REDIRECT') && BLOCK_REDIRECT !== '') {
        header('Location: ' . BLOCK_REDIRECT);
    } else {
        http_response_code(403);
        echo 'Forbidden';
    }
    exit;
}

