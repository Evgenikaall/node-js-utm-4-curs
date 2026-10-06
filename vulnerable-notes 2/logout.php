<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    fail_request(405, 'Метод не поддерживается.');
}
verify_csrf();
$userId = $_SESSION['user_id'] ?? null;
$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $parameters = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $parameters['path'], $parameters['domain'], $parameters['secure'], $parameters['httponly']);
}
session_destroy();
secure_log('logout', ['user_id' => $userId]);
redirect('login.php');
