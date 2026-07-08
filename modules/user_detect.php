<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/load_env.php';
require_once __DIR__ . '/../logs_error/error_log.php';
if (!function_exists('isBotUserAgent')) {
    function isBotUserAgent(): bool {
        $botSignatures = ['bot', 'crawl', 'spider', 'curl', 'wget', 'python', 'go-http-client'];
        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');
        foreach ($botSignatures as $bot) {
            if (strpos($userAgent, $bot) !== false) {
                return true;
            }
        }
        return false;
    }
}

if (!function_exists('exitWithBlock')) {
    function exitWithBlock(string $reason): void {
        http_response_code(403);
        echo "<h1>403 - Accès refusé</h1><p>" . htmlspecialchars($reason, ENT_QUOTES, 'UTF-8') . "</p>";
        exit;
    }
}

if (!function_exists('getUserIP')) {
    function getUserIP(): string {
        $ipHeaders = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        foreach ($ipHeaders as $header) {
            if (!empty($_SERVER[$header])) {
                $ips = explode(',', $_SERVER[$header]);
                return trim(end($ips));
            }
        }
        return 'UNKNOWN';
    }
}


class MobileDetect {
    protected string $userAgent;
    protected string $accept;
    protected bool $isMobile = false;
    protected bool $isTablet = false;

    protected array $devices = [
        'android'           => 'android',
        'blackberry'        => 'blackberry|rim[0-9]+',
        'iphone'            => 'iphone',
        'ipod'              => 'ipod',
        'opera'             => 'opera mini|opera mobi',
        'palm'              => '(avantgo|blazer|elaine|hiptop|palm|plucker|xiino)',
        'windows_phone'     => 'windows phone',
        'windows_mobile'    => 'windows ce; (iemobile|ppc|smartphone)',
        'kindle'            => 'kindle|silk',
        'nokia'             => 'nokia|series ?[0-9]+',
        'symbian'           => 'symbian|symbos',
        'maemo'             => 'maemo',
        'fennec'            => 'fennec',
        'bb10'              => 'bb10|playbook|blackberry.*version\/10',
        'meego'             => 'meego',
        'mobile_safari'     => 'mobile safari',
        'tizen'             => 'tizen',
        'webos'             => 'webos|hpwos',
        'bada'              => 'bada',
        'huawei'            => 'huawei|honor',
        'xiaomi'            => 'redmi|mi\\s|xiaomi|poco',
        'oppo'              => 'oppo',
        'vivo'              => 'vivo',
        'realme'            => 'realme',
        'oneplus'           => 'oneplus',
        'sony'              => 'sonyericsson|xperia',
        'lg'                => 'lg[\\-\\/]|lg\\s|lg[0-9]+',
        'htc'               => 'htc|desire|sensation|wildfire|hero',
        'motorola'          => 'moto|mot\\-|droid|xt[0-9]+',
        'samsung'           => 'samsung|galaxy|gt\\-|sm\\-',
        'zte'               => 'zte|blade',
        'alcatel'           => 'alcatel|one\\stouch',
        'asus'              => 'asus|zenfone',
        'generic'           => '(mobile|mmp|midp|pda|pocket|psp|symbian|treo|up.browser|up.link|vodafone|wap|phone|smartphone)',
    ];

    protected array $tablets = [
        'ipad'              => 'ipad',
        'android_tablet'    => 'android(?!.*mobile)',
        'kindle'            => 'kindle|silk',
        'nexus_tablet'      => 'nexus\\s[0-9]+|nexus 7|nexus 10|nexus 9',
        'playbook'          => 'playbook',
        'xoom'              => 'xoom|sch-i800',
        'galaxy_tab'        => 'sm\\-t[0-9]+|sm\\-t\\w+|galaxy tab|tab\\s[0-9]+',
        'surface'           => 'surface\\srt|surface|windows nt [0-9.]+; arm; tablet',
        'hp_tablet'         => 'hp\\stablet|touchpad',
        'lenovo_tablet'     => 'thinkpad|ideatab',
        'dell_tablet'       => 'venue|streak',
        'yarvik_tablet'     => 'yarvik',
        'medion_tablet'     => 'medion',
        'arnova_tablet'     => 'arnova',
        'archos_tablet'     => 'archos',
        'aoc_tablet'        => 'aoc\\s',
        'bq_tablet'         => 'bq\\s',
        'tesco_tablet'      => 'tesco',
        'le_pan_tablet'     => 'le\\span',
        'fujitsu_tablet'    => 'stylistic',
        'qmv_tablet'        => 'qmv7a',
        'odys_tablet'       => 'loox',
        'captiva_tablet'    => 'captiva',
        'iconbit_tablet'    => 'netTAB|ultraTAB',
        'teclast_tablet'    => 'teclast',
        'onda_tablet'       => 'onda',
        'jxd_tablet'        => 'jxd',
        'pointofview_tablet'=> 'pointofview',
        'overmax_tablet'    => 'overmax',
        'barnesandnoble'    => 'bn\\srv[0-9]+',
        'generic_tablet'    => '(tablet|tab)[^a-z]|tablet pc',
    ];

    public function __construct() {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $this->accept = $_SERVER['HTTP_ACCEPT'] ?? '';

        if ($this->hasWapProfile() || $this->hasWapAccept()) {
            $this->isMobile = true;
        } else {
            $this->detectTablet();
            if (!$this->isTablet) {
                $this->detectMobile();
            }
        }
    }

    protected function hasWapProfile(): bool {
        return isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']);
    }

    protected function hasWapAccept(): bool {
        return stripos($this->accept, 'text/vnd.wap.wml') !== false
            || stripos($this->accept, 'application/vnd.wap.xhtml+xml') !== false;
    }

    protected function detectTablet(): void {
        foreach ($this->tablets as $pattern) {
            if (preg_match("/$pattern/i", $this->userAgent)) {
                $this->isTablet = true;
                break;
            }
        }
    }

    protected function detectMobile(): void {
        foreach ($this->devices as $pattern) {
            if (preg_match("/$pattern/i", $this->userAgent)) {
                $this->isMobile = true;
                break;
            }
        }
    }

    public function isMobile(): bool {
        return $this->isMobile;
    }

    public function isTablet(): bool {
        return $this->isTablet;
    }

    public function isDesktop(): bool {
        return !$this->isMobile && !$this->isTablet;
    }

    public function getDeviceType(): string {
        if ($this->isTablet()) return 'tablet';
        if ($this->isMobile()) return 'mobile';
        return 'desktop';
    }

    public function getUserAgent(): string {
        return $this->userAgent;
    }

    public function getDeviceModel(): string {
    $ua = strtolower($this->userAgent);

    $patterns = [
        // Smartphones & Tablettes
        'Samsung' => '/sm-[a-z0-9\-]+/',                      
        'Samsung Tablet' => '/sm-t[0-9]+/',                   
        'Xiaomi Redmi' => '/redmi\s[a-z0-9\-]+/',             
        'Xiaomi Mi' => '/mi\s[a-z0-9\-]+/',                   
        'Google Pixel' => '/pixel\s[0-9a-z\-]+/',             
        'OnePlus' => '/oneplus\s[a-z0-9\-]+/',                
        'Motorola' => '/moto\s?[a-z0-9\-]+/',                 
        'Huawei' => '/(vog-l29|ane-lx1|mar-lx1a|huawei\s[a-z0-9\-]+)/',
        'iPhone' => '/iphone(?:\sos\s)?[\d_]+/',               
        'iPad' => '/ipad(?:.*os\s)?[\d_]+/',                   
        'Sony Xperia' => '/xperia\s[a-z0-9\-]+/',              
        'LG' => '/lg[\-\/\s]?[a-z0-9\-]+/',                    
        'HTC' => '/htc[\-\/\s]?[a-z0-9\-]+/',                  
        'BlackBerry' => '/blackberry\s?[a-z0-9\-]+/',          
        'Nokia' => '/nokia\s?[a-z0-9\-]+/',                     
        'Asus' => '/asus\s?[a-z0-9\-]+/',                       
        'Realme' => '/realme\s[a-z0-9\-]+/',                    
        'Vivo' => '/vivo\s[a-z0-9\-]+/',                        
        'Oppo' => '/oppo\s[a-z0-9\-]+/',                        
        'Lenovo' => '/lenovo\s[a-z0-9\-]+/',                    
        'Alcatel' => '/alcatel\s[a-z0-9\-]+/',                  
        'ZTE' => '/zte\s[a-z0-9\-]+/',                          
        'Amazon Kindle' => '/kindle|silk/',                     
        'Meizu' => '/meizu\s?[a-z0-9\-]+/',                     
        'Google Nexus' => '/nexus\s[0-9]+/',                    

        // PC / Desktop
        'Windows PC' => '/windows nt ([0-9\.]+)/',              
        'Macintosh' => '/macintosh; intel mac os x ([0-9_\.]+)/',
        'Linux' => '/linux/',                                    
        'Ubuntu' => '/ubuntu/',                                  
        'Fedora' => '/fedora/',                                  
        'Debian' => '/debian/',                                  
        'Chrome OS' => '/cros/',                                 

        // Consoles de jeu
        'PlayStation 5' => '/playstation 5/',                    
        'PlayStation 4' => '/playstation 4/',                    
        'Xbox Series X' => '/xbox series x/',                    
        'Xbox One' => '/xbox one/',                              
        'Nintendo Switch' => '/nintendo switch/',               

        // TV & Box
        'Samsung Smart TV' => '/smart-tv|smarttv|smarttv samsung/',  
        'LG Smart TV' => '/lg smarttv|webos/',                   
        'Sony TV' => '/bravia|sony tv/',                         
        'Amazon Fire TV' => '/aftt|fire tv/',                    
        'Roku' => '/roku/',                                       
        'Apple TV' => '/apple tv/',                               

        // Robots, crawlers (optionnel)
        'Googlebot' => '/googlebot/',                            
        'Bingbot' => '/bingbot/',                                
        'Baiduspider' => '/baiduspider/',                        

        // Autres appareils
        'Kindle' => '/kindle/',                                  
        'Chromebook' => '/cros/',                                
        'Wear OS' => '/wear os|android wear/',                   

        // Tablettes Android génériques
        'Android Tablet' => '/android(?!.*mobile)/',             

        // Smartwatch (exemples)
        'Apple Watch' => '/applewatch/',                          
        'Samsung Gear' => '/sm-r[0-9]+/',                         

        // Autres OS mobiles
        'Windows Phone' => '/windows phone/',                     
        'BlackBerry OS' => '/blackberry/',                        

        // Autres marques possibles
        'Fairphone' => '/fairphone/',                             
        'BQ' => '/bq[a-z0-9\-]+/',                                
        'Cat Phone' => '/cat[ ]?phone/',                          

        // Général fallback pour Android devices
        'Android' => '/android/',                                 
    ];

    foreach ($patterns as $brand => $pattern) {
        if (preg_match($pattern, $ua, $matches)) {
            $modelRaw = trim($matches[0]);

            // Cas spéciaux Windows et Mac avec version
            if ($brand === 'Windows PC' && isset($matches[1])) {
                return "Windows PC NT " . $matches[1];
            }
            if ($brand === 'Macintosh' && isset($matches[1])) {
                $version = str_replace('_', '.', $matches[1]);
                return "Macintosh macOS " . $version;
            }

            // Nettoyage du résultat
            $modelClean = preg_replace('/\s+/', ' ', $modelRaw);
            $modelClean = strtoupper($modelClean);

            return $brand . ' ' . $modelClean;
        }
    }

    return 'Unknown';
}
}


// === CONFIGURATION ===
$apiKey = 'QqyiAbxJ5x0Iqsf';
$allowedIps = ['127.0.0.1', '::1'];
$ip = getUserIP();
$mobileDetect = new MobileDetect();

date_default_timezone_set('Europe/Paris');
if (in_array($ip, $allowedIps, true)) {
    $visitor = [
        'timestamp'    => date('d-m-Y H:i:s'),
        'ip'           => $ip,
        'country'      => '',
        'isp'          => '',
        'proxy'        => false,
        'bot'          => false,
        'mobile'       => $mobileDetect->isMobile(),
        'device_type'  => $mobileDetect->getDeviceType(),
        'device_model' => $mobileDetect->getDeviceModel(),
        'as'           => '' !== '' ? '' : '❌',         
        'city'         => '' !== '' ? '' : '❌',
        'org'          => '' !== '' ? '' : '❌',
        'region'       => '' !== '' ? '' : '❌',
        'regionName'   => '' !== '' ? '' : '❌',
        'timezone'     => '' !== '' ? '' : '❌',
        'zip'          => '' !== '' ? '' : '❌',
    ];
    $_SESSION['visitor'] = $visitor;
    saveVisitorJson($visitor);
    return;
}


$fields = 'status,message,countryCode,country,region,regionName,city,zip,timezone,isp,org,as,proxy,query';
$apiUrl = "http://pro.ip-api.com/json/{$ip}?key={$apiKey}&fields={$fields}";

$response = @file_get_contents($apiUrl);
$data = @json_decode($response, true);

// Gestion d'erreur pour l'API IP
if ($response === false || $data === null || !isset($data['status']) || $data['status'] !== 'success') {
    logWarning("Erreur API IP pour l'IP: {$ip}", [
        'response' => $response,
        'data' => $data,
        'api_url' => $apiUrl
    ]);
    
    // En cas d'erreur API, utiliser des valeurs par défaut
    $data = [
        'query' => $ip,
        'country' => 'UNKNOWN',
        'countryCode' => 'UNKNOWN',
        'region' => '',
        'regionName' => '',
        'city' => '',
        'zip' => '',
        'timezone' => '',
        'isp' => '',
        'org' => '',
        'as' => '',
        'proxy' => false
    ];
}

$isProxy = !empty($data['proxy']) && $data['proxy'] === true;
$isBot = isBotUserAgent();

$reasons = [];
if ($isProxy) $reasons[] = 'Proxy/VPN';
if ($isBot) $reasons[] = 'Bot';

$visitor = [
    'timestamp'    => date('d-m-Y H:i:s'),
    'ip' => $data['query'] ?? $ip,
    'country' => $data['country'] ?? 'UNKNOWN',
    'countryCode' => $data['countryCode'] ?? 'UNKNOWN',
    'region' => $data['region'] ?? '',
    'regionName' => $data['regionName'] ?? '',
    'city' => $data['city'] ?? '',
    'zip' => $data['zip'] ?? '',
    'timezone' => $data['timezone'] ?? '',
    'isp' => $data['isp'] ?? '',
    'org' => $data['org'] ?? '',
    'as' => $data['as'] ?? '',
    'proxy' => $isProxy,
    'bot' => $isBot,
    'mobile' => $mobileDetect->isMobile(),
    'device_type' => $mobileDetect->getDeviceType(),
    'device_model' => $mobileDetect->getDeviceModel(),
];
$_SESSION['visitor'] = $visitor;

if (!visitorAlreadyExists($visitor['ip'])) {
    sendToTelegram($visitor);
    saveVisitorJson($visitor);
}



function visitorAlreadyExists(string $ip): bool {
    $logDir = realpath(__DIR__ . '/../Panel/logs');
    if ($logDir === false) return false; // Pas de logs = pas d’existant

    $jsonFile = $logDir . '/visitors.json';
    if (!file_exists($jsonFile)) return false;

    $existing = json_decode(file_get_contents($jsonFile), true);
    if (!is_array($existing)) return false;

    foreach ($existing as $visitor) {
        if (isset($visitor['ip']) && $visitor['ip'] === $ip) {
            return true; // Déjà présent
        }
    }
    return false;
}



function sendToTelegram(array $visitor): void {
    $bot_token = $_ENV['BOT_TOKEN'] ?? '';
    $chat_id = $_ENV['CHAT_ID'] ?? '';

    if (empty($bot_token) || empty($chat_id)) {
        return;
    }

    $message = "🆕 <b>Nouveau visiteur détecté</b>\n"
             . "📍 <b>IP :</b> {$visitor['ip']}\n"
             . "🌍 <b>Pays :</b> {$visitor['country']} ({$visitor['countryCode']})\n"
             . "🏙️ <b>Ville :</b> {$visitor['city']} - {$visitor['regionName']}\n"
             . "📶 <b>FAI :</b> {$visitor['isp']} / {$visitor['org']}\n"
             . "📱 <b>Appareil :</b> {$visitor['device_model']} ({$visitor['device_type']})\n"
             . "⏰ <b>Heure :</b> {$visitor['timestamp']}\n"
             . "🕵️‍♂️ <b>Proxy :</b> " . ($visitor['proxy'] ? '✅' : '❌') . " | <b>Bot :</b> " . ($visitor['bot'] ? '✅' : '❌');

    $url = "https://api.telegram.org/bot{$bot_token}/sendMessage";
    $params = [
        'chat_id' => $chat_id,
        'text'    => $message,
        'parse_mode' => 'HTML',
    ];

    @file_get_contents($url . '?' . http_build_query($params));
}




function saveVisitorJson(array $visitor): void {
    // Chemin vers dossier logs dans ../Panel/logs
    $logDir = realpath(__DIR__ . '/../Panel/logs');
    if ($logDir === false) {
        // Crée dossier si n'existe pas
        $logDir = __DIR__ . '/../Panel/logs';
        mkdir($logDir, 0755, true);
    }

    $jsonFile = $logDir . '/visitors.json';
    $existing = [];

    if (file_exists($jsonFile)) {
        $existing = json_decode(file_get_contents($jsonFile), true) ?? [];
    }

    $existing[] = $visitor;

    file_put_contents($jsonFile, json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}


?>
