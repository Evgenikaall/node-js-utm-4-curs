<?php
require_once 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $sql = "INSERT INTO users (name, email, password, role)
                VALUES ('$name', '$email', '$password', 'user')";
        $pdo->exec($sql);
        $message = 'Регистрация выполнена. Теперь можно войти.';
    } catch (PDOException $exception) {
        $message = 'Ошибка базы данных: ' . $exception->getMessage();
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Регистрация | Vulnerable Notes</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="card narrow">
    <div class="warning">Учебный проект. Использовать только локально.</div>
    <h1>Регистрация</h1>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Имя</label>
        <input type="text" name="name">

        <label>Электронная почта</label>
        <input type="text" name="email">

        <label>Пароль</label>
        <input type="text" name="password">

        <button type="submit">Зарегистрироваться</button>
    </form>

    <p><a href="login.php">Уже есть аккаунт? Войти</a></p>
</main>
</body>
</html>

