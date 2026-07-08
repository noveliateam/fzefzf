<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['user_id'])) {
    // On récupère l’ID depuis la session
    $userId = $_SESSION['user_id'];
    } else {
        // Session vide, on regarde le cookie
        if (!isset($_COOKIE['uid'])) {
            // Pas de cookie : on crée un nouvel UID unique avec timestamp et IP
            $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
            $timestamp = microtime(true);
            $uid = 'user_' . substr(md5($ip . $timestamp . uniqid()), 0, 16);

            // Cookie valide 7 jours, sécurisé et HttpOnly si HTTPS
            setcookie('uid', $uid, time() + 7 * 24 * 3600, "/", "", isset($_SERVER['HTTPS']), true);

            // On met aussi en session pour accès rapide
            $_SESSION['user_id'] = $uid;
            $userId = $uid;
        } else {
            // Cookie présent, on initialise la session avec sa valeur
            $_SESSION['user_id'] = $_COOKIE['uid'];
            $userId = $_COOKIE['uid'];
        }
    }
