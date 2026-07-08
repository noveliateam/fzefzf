<?php
require_once __DIR__ . '/env_loader.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($email === $_ENV['ADMIN_LOGIN'] && $password === $_ENV['ADMIN_PASSWORD']) {
        
        session_start();
        $_SESSION['admin_logged_in'] = true;
        header('Location: ../dashboard.php');
        exit;
    } else {
        header('Location: ../index.php?error=1');
        exit;
    }
}
?>