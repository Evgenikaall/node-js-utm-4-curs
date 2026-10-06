<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

$notes = $pdo->query(
    "SELECT * FROM notes WHERE user_id = $userId ORDER BY created_at DESC"
)->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Мои заметки | Vulnerable Notes</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <strong>Vulnerable Notes</strong>
    <nav>
        <a href="dashboard.php">Мои заметки</a>
        <a href="add_note.php">Добавить</a>
        <a href="admin.php">Администрирование</a>
        <a href="logout.php">Выйти</a>
    </nav>
</header>

<main class="container">
    <h1>Здравствуйте, <?= $_SESSION['name'] ?>!</h1>
    <p>Ваш уровень доступа: <strong><?= $_SESSION['role'] ?></strong></p>

    <div class="actions">
        <a class="button" href="add_note.php">Новая заметка</a>
    </div>

    <?php if (!$notes): ?>
        <div class="empty">У вас пока нет заметок.</div>
    <?php endif; ?>

    <section class="notes">
        <?php foreach ($notes as $note): ?>
            <article class="note">
                <h2><?= $note['title'] ?></h2>
                <div><?= nl2br($note['content']) ?></div>
                <small><?= $note['created_at'] ?></small>
                <div class="note-actions">
                    <a href="edit_note.php?id=<?= $note['id'] ?>">Изменить</a>
                    <a class="danger" href="delete_note.php?id=<?= $note['id'] ?>">Удалить</a>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</main>
</body>
</html>

