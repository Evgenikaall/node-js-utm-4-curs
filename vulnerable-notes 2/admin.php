<?php
require_once 'config.php';

$adminId = require_admin();
try {
    $users = $pdo->query('SELECT id, name, email, role, created_at FROM users ORDER BY id')->fetchAll();
} catch (PDOException $exception) {
    secure_log('admin_users_list_failed', ['user_id' => $adminId, 'code' => $exception->getCode()]);
    fail_request(500, 'Не удалось загрузить список пользователей.');
}
?>
<!doctype html>
<html lang="ru">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Администрирование</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<main class="container">
    <h1>Пользователи системы</h1>
    <table><thead><tr><th>ID</th><th>Имя</th><th>Email</th><th>Роль</th><th>Создан</th></tr></thead><tbody>
    <?php foreach ($users as $user): ?>
        <tr><td><?= (int) $user['id'] ?></td><td><?= e($user['name']) ?></td><td><?= e($user['email']) ?></td><td><?= e($user['role']) ?></td><td><?= e($user['created_at']) ?></td></tr>
    <?php endforeach; ?>
    </tbody></table>
    <p><a class="button secondary" href="dashboard.php">Назад</a></p>
</main>
</body>
</html>
