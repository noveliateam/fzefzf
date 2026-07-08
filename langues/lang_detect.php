<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Définir ici la langue forcée, ou rien pour ne pas forcer
define('FORCE_LANG', ''); // ex: 'fr', 'en', 'es', etc. ou null

function detectBrowserLanguage($availableLanguages, $default = 'en') {
    if (!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        return $default;
    }

    $langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);

    foreach ($langs as $lang) {
        
        $langCode = strtolower(substr(trim($lang), 0, 2));

        if (in_array($langCode, $availableLanguages)) {
            return $langCode;
        }
    }

    return $default;
}

$langData = json_decode(file_get_contents(__DIR__ . '/lang.json'), true);
$availableLanguages = array_keys($langData);

if (FORCE_LANG !== null && in_array(FORCE_LANG, $availableLanguages)) {
   
    $_SESSION['lang'] = FORCE_LANG;
} else {
   
    if (isset($_GET['lang']) && in_array($_GET['lang'], $availableLanguages)) {
        $_SESSION['lang'] = $_GET['lang'];
    }

    
    if (!isset($_SESSION['lang'])) {
        $_SESSION['lang'] = detectBrowserLanguage($availableLanguages);
    }
}

$lang = $_SESSION['lang'];
$tr = $langData[$lang] ?? $langData['en'];//Langue de secours si pas trouvée
$langCode = $lang;
