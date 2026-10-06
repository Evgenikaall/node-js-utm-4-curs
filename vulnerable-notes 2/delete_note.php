<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? '0';

$pdo->exec("DELETE FROM notes WHERE id = $id");

header('Location: dashboard.php');
exit;

