<?php
require_once 'config.php';

$userId = require_login();
$message = '';
$title = '';
$content = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        verify_csrf();
        $title = required_text($_POST['title'] ?? null, 'Заголовок', 255);
        $content = required_text($_POST['content'] ?? null, 'Текст заметки', 10_000);
        $statement = $pdo->prepare('INSERT INTO notes (user_id, title, content) VALUES (:user_id, :title, :content)');
        $statement->execute([':user_id' => $userId, ':title' => $title, ':content' => $content]);
        secure_log('note_created', ['user_id' => $userId, 'note_id' => (int) $pdo->lastInsertId()]);
        redirect('dashboard.php');
    } catch (InvalidArgumentException $exception) {
        $message = $exception->getMessage();
    } catch (PDOException $exception) {
        secure_log('note_create_failed', ['user_id' => $userId, 'code' => $exception->getCode()]);
        $message = 'Не удалось сохранить заметку. Попробуйте позже.';
    }
}
?>
<!doctype html>
<html lang="ru">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Новая заметка</title><link rel="stylesheet" href="assets/style.css"></head>
<body><main class="card"><h1>Новая заметка</h1>
<?php if ($message): ?><div class="message error"><?= e($message) ?></div><?php endif; ?>
<form method="post" novalidate><?= csrf_field() ?>
<label for="title">Заголовок</label><input id="title" type="text" name="title" maxlength="255" value="<?= e($title) ?>" required>
<label for="content">Текст</label><textarea id="content" name="content" rows="8" maxlength="10000" required><?= e($content) ?></textarea>
<button type="submit">Сохранить</button><a class="button secondary" href="dashboard.php">Отмена</a>
</form></main></body>
</html>
