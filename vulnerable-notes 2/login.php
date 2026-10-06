<?php
require_once 'config.php';

$message = isset($_GET['registered']) ? 'Регистрация выполнена. Теперь можно войти.' : '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        verify_csrf();
        $email = valid_email($_POST['email'] ?? null);
        $password = valid_password($_POST['password'] ?? null);
        $statement = $pdo->prepare('SELECT id, name, password, role FROM users WHERE email = :email LIMIT 1');
        $statement->execute([':email' => $email]);
        $user = $statement->fetch();

        if (!$user || !password_verify($password, $user['password'])) {
            secure_log('login_failed', ['email' => $email]);
            $message = 'Пользователь не найден или пароль неверный.';
        } else {
            session_regenerate_id(true);
            $_SESSION['user_id'] = (int) $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            secure_log('login_success', ['user_id' => (int) $user['id']]);
            redirect('dashboard.php');
        }
    } catch (InvalidArgumentException $exception) {
        $message = 'Пользователь не найден или пароль неверный.';
    } catch (PDOException $exception) {
        secure_log('login_error', ['code' => $exception->getCode()]);
        $message = 'Не удалось выполнить вход. Попробуйте позже.';
    }
}
?>
<!doctype html>
<html lang="ru">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Вход | Vulnerable Notes</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<main class="card narrow">
    <h1>Vulnerable Notes</h1>
    <?php if ($message): ?><div class="message<?= isset($_GET['registered']) ? '' : ' error' ?>"><?= e($message) ?></div><?php endif; ?>
    <form method="post" novalidate>
        <?= csrf_field() ?>
        <label for="email">Электронная почта</label>
        <input id="email" type="email" name="email" maxlength="150" value="<?= e($email) ?>" required autocomplete="email">
        <label for="password">Пароль</label>
        <input id="password" type="password" name="password" minlength="12" maxlength="255" required autocomplete="current-password">
        <button type="submit">Войти</button>
    </form>
    <p><a href="register.php">Создать аккаунт</a></p>
</main>
</body>
</html>
