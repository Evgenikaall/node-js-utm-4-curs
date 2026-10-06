<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';
    $userId = $_SESSION['user_id'];

    try {
        $sql = "INSERT INTO notes (user_id, title, content)
                VALUES ($userId, '$title', '$content')";
        $pdo->exec($sql);
        header('Location: dashboard.php');
        exit;
    } catch (PDOException $exception) {
        $message = $exception->getMessage();
    }
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Новая заметка</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="card">
    <h1>Новая заметка</h1>
    <?php if ($message): ?><div class="message error"><?= $message ?></div><?php endif; ?>
    <form method="post">
        <label>Заголовок</label>
        <input type="text" name="title">

        <label>Текст</label>
        <textarea name="content" rows="8"></textarea>

        <button type="submit">Сохранить</button>
        <a class="button secondary" href="dashboard.php">Отмена</a>
    </form>
</main>
</body>
</html>

