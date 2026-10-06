<?php
require_once 'config.php';

$userId = require_login();
try {
    $statement = $pdo->prepare('SELECT id, title, content, created_at FROM notes WHERE user_id = :user_id ORDER BY created_at DESC');
    $statement->execute([':user_id' => $userId]);
    $notes = $statement->fetchAll();
} catch (PDOException $exception) {
    secure_log('notes_list_failed', ['user_id' => $userId, 'code' => $exception->getCode()]);
    fail_request(500, 'Не удалось загрузить заметки. Попробуйте позже.');
}
?>
<!doctype html>
<html lang="ru">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Мои заметки | Vulnerable Notes</title><link rel="stylesheet" href="assets/style.css"></head>
<body>
<header class="topbar">
    <strong>Vulnerable Notes</strong>
    <nav>
        <a href="dashboard.php">Мои заметки</a><a href="add_note.php">Добавить</a>
        <?php if (($_SESSION['role'] ?? '') === 'admin'): ?><a href="admin.php">Администрирование</a><?php endif; ?>
        <form class="inline-form" method="post" action="logout.php"><?= csrf_field() ?><button type="submit">Выйти</button></form>
    </nav>
</header>
<main class="container">
    <h1>Здравствуйте, <?= e($_SESSION['name'] ?? '') ?>!</h1>
    <p>Ваш уровень доступа: <strong><?= e($_SESSION['role'] ?? '') ?></strong></p>
    <div class="actions"><a class="button" href="add_note.php">Новая заметка</a></div>
    <?php if (!$notes): ?><div class="empty">У вас пока нет заметок.</div><?php endif; ?>
    <section class="notes">
        <?php foreach ($notes as $note): ?>
            <article class="note">
                <h2><?= e($note['title']) ?></h2>
                <div><?= nl2br(e($note['content'])) ?></div>
                <small><?= e($note['created_at']) ?></small>
                <div class="note-actions">
                    <a href="edit_note.php?id=<?= (int) $note['id'] ?>">Изменить</a>
                    <form class="inline-form" method="post" action="delete_note.php">
                        <?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $note['id'] ?>">
                        <button class="danger" type="submit">Удалить</button>
                    </form>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</main>
</body>
</html>
