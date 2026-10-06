<?php
declare(strict_types=1);

ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);

function secure_log(string $event, array $context = []): void
{
    $directory = __DIR__ . '/storage';
    if (!is_dir($directory)) {
        mkdir($directory, 0700, true);
    }

    $record = ['time' => gmdate('c'), 'event' => $event, 'context' => $context];
    $logFile = $directory . '/app.log';
    file_put_contents($logFile, json_encode($record, JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND | LOCK_EX);
    @chmod($logFile, 0600);
}

function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function redirect(string $location): void
{
    header('Location: ' . $location, true, 303);
    exit;
}

function fail_request(int $status, string $message): void
{
    http_response_code($status);
    exit($message);
}

function string_length(string $value): int
{
    return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
}

function required_text(mixed $value, string $field, int $maximumLength): string
{
    if (!is_string($value)) {
        throw new InvalidArgumentException("Поле «{$field}» имеет неверный формат.");
    }

    $value = trim($value);
    if ($value === '' || string_length($value) > $maximumLength) {
        throw new InvalidArgumentException("Поле «{$field}» обязательно и не должно превышать {$maximumLength} символов.");
    }

    return $value;
}

function valid_email(mixed $value): string
{
    $email = required_text($value, 'Электронная почта', 150);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new InvalidArgumentException('Введите корректный адрес электронной почты.');
    }

    return function_exists('mb_strtolower') ? mb_strtolower($email, 'UTF-8') : strtolower($email);
}

function valid_password(mixed $value): string
{
    if (!is_string($value) || strlen($value) < 12 || strlen($value) > 255) {
        throw new InvalidArgumentException('Пароль должен содержать от 12 до 255 символов.');
    }

    return $value;
}

function positive_id(mixed $value): int
{
    $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($id === false) {
        fail_request(404, 'Запрошенная заметка не найдена.');
    }

    return $id;
}

function require_login(): int
{
    $userId = $_SESSION['user_id'] ?? null;
    if (!is_int($userId) && !ctype_digit((string) $userId)) {
        redirect('login.php');
    }

    return (int) $userId;
}

function require_admin(): int
{
    $userId = require_login();
    if (($_SESSION['role'] ?? '') !== 'admin') {
        secure_log('access_denied', ['user_id' => $userId, 'resource' => 'admin']);
        fail_request(403, 'Доступ запрещён.');
    }

    return $userId;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $token = $_POST['csrf_token'] ?? '';
    if (!is_string($token) || !hash_equals(csrf_token(), $token)) {
        secure_log('csrf_rejected', ['user_id' => $_SESSION['user_id'] ?? null]);
        fail_request(403, 'Недействительный защитный токен. Обновите страницу и повторите действие.');
    }
}

set_exception_handler(function (Throwable $exception): void {
    secure_log('unhandled_exception', [
        'type' => get_class($exception),
        'code' => $exception->getCode(),
    ]);
    fail_request(500, 'Сервис временно недоступен. Попробуйте позже.');
});

$isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
    || (($_SERVER['SERVER_PORT'] ?? null) === '443');
session_name('vulnerable_notes_session');
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => $isHttps,
    'httponly' => true,
    'samesite' => 'Lax',
]);
ini_set('session.use_strict_mode', '1');
session_start();

$host = getenv('NOTES_DB_HOST') ?: 'localhost';
$database = getenv('NOTES_DB_NAME') ?: 'vulnerable_notes';
$username = getenv('NOTES_DB_USER') ?: 'root';
$password = getenv('NOTES_DB_PASSWORD') ?: '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$database};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $exception) {
    secure_log('database_connection_failed', ['code' => $exception->getCode()]);
    fail_request(500, 'Сервис временно недоступен. Попробуйте позже.');
}
