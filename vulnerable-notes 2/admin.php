<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$users = $pdo->query(
    'SELECT id, name, email, password, role FROM users ORDER BY id'
)->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Администрирование</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container">
    <h1>Пользователи системы</h1>
    <p class="warning">Эта страница должна быть доступна только администратору.</p>
    <table>
        <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Пароль</th>
            <th>Роль</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['name'] ?></td>
                <td><?= $user['email'] ?></td>
                <td><?= $user['password'] ?></td>
                <td><?= $user['role'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <p><a class="button secondary" href="dashboard.php">Назад</a></p>
</main>
</body>
</html>

