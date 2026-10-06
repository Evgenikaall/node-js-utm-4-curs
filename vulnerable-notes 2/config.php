<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

$host = 'localhost';
$database = 'vulnerable_notes';
$username = 'root';
$password = '';

$pdo = new PDO(
    "mysql:host=$host;dbname=$database;charset=utf8mb4",
    $username,
    $password
);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

session_start();

