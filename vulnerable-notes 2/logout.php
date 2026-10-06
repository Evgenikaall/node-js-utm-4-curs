<?php
require_once 'config.php';

unset($_SESSION['user_id']);

header('Location: login.php');
exit;

