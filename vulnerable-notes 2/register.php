<?php
require_once 'config.php';

$message = '';
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        verify_csrf();
        $name = required_text($_POST['name'] ?? null, 'Имя', 100);
        $email = valid_email($_POST['email'] ?? null);
        $password = valid_password($_POST['password'] ?? null);

        $statement = $pdo->prepare(
            'INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, :role)'
        );
        $statement->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':role' => 'user',
        ]);
        secure_log('registration_success', ['user_id' => (int) $pdo->lastInsertId()]);
        redirect('login.php?registered=1');
    } catch (InvalidArgumentException $exception) {
        $message = $exception->getMessage();
    } catch (PDOException $exception) {
        secure_log('registration_failed', ['code' => $exception->getCode()]);
        $message = 'Не удалось завершить регистрацию. Возможно, этот адрес уже используется.';
    }
}
?>
<!doctype html>
<html lang="ru">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Регистрация | Vulnerable Notes</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<main class="card narrow">
    <h1>Регистрация</h1>
    <?php if ($message): ?><div class="message error"><?= e($message) ?></div><?php endif; ?>
    <form method="post" novalidate>
        <?= csrf_field() ?>
        <label for="name">Имя</label>
        <input id="name" type="text" name="name" maxlength="100" value="<?= e($name) ?>" required autocomplete="name">
        <label for="email">Электронная почта</label>
        <input id="email" type="email" name="email" maxlength="150" value="<?= e($email) ?>" required autocomplete="email">
        <label for="password">Пароль</label>
        <input id="password" type="password" name="password" minlength="12" maxlength="255" required autocomplete="new-password">
        <button type="submit">Зарегистрироваться</button>
    </form>
    <p><a href="login.php">Уже есть аккаунт? Войти</a></p>
</main>
</body>
</html>
