<?php
require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $sql = "SELECT * FROM users
                WHERE email = '$email' AND password = '$password'";
        $user = $pdo->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            header('Location: dashboard.php');
            exit;
        }

        $message = 'Пользователь не найден или пароль неправильный.';
    } catch (PDOException $exception) {
        $message = 'Ошибка SQL: ' . $exception->getMessage();
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Вход | Vulnerable Notes</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="card narrow">
    <div class="warning">Учебный проект. Использовать только локально.</div>
    <h1>Vulnerable Notes</h1>
    <p class="muted">Приложение намеренно содержит уязвимости.</p>

    <?php if ($message): ?>
        <div class="message error"><?= $message ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Электронная почта</label>
        <input type="text" name="email">

        <label>Пароль</label>
        <input type="text" name="password">

        <button type="submit">Войти</button>
    </form>

    <p><a href="register.php">Создать аккаунт</a></p>
</main>
</body>
</html>

