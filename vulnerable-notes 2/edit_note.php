<?php
require_once 'config.php';

$userId = require_login();
$id = positive_id($_GET['id'] ?? null);
$message = '';
try {
    $statement = $pdo->prepare('SELECT id, title, content FROM notes WHERE id = :id AND user_id = :user_id LIMIT 1');
    $statement->execute([':id' => $id, ':user_id' => $userId]);
    $note = $statement->fetch();
    if (!$note) {
        secure_log('note_access_denied', ['user_id' => $userId, 'note_id' => $id]);
        fail_request(404, 'Запрошенная заметка не найдена.');
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        verify_csrf();
        $title = required_text($_POST['title'] ?? null, 'Заголовок', 255);
        $content = required_text($_POST['content'] ?? null, 'Текст заметки', 10_000);
        $update = $pdo->prepare('UPDATE notes SET title = :title, content = :content WHERE id = :id AND user_id = :user_id');
        $update->execute([':title' => $title, ':content' => $content, ':id' => $id, ':user_id' => $userId]);
        secure_log('note_updated', ['user_id' => $userId, 'note_id' => $id]);
        redirect('dashboard.php');
    }
} catch (InvalidArgumentException $exception) {
    $message = $exception->getMessage();
} catch (PDOException $exception) {
    secure_log('note_update_failed', ['user_id' => $userId, 'note_id' => $id, 'code' => $exception->getCode()]);
    fail_request(500, 'Не удалось обработать заметку. Попробуйте позже.');
}
?>
<!doctype html>
<html lang="ru">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Изменение заметки</title><link rel="stylesheet" href="assets/style.css"></head>
<body><main class="card"><h1>Изменение заметки</h1>
<?php if ($message): ?><div class="message error"><?= e($message) ?></div><?php endif; ?>
<form method="post" novalidate><?= csrf_field() ?>
<label for="title">Заголовок</label><input id="title" type="text" name="title" maxlength="255" value="<?= e($note['title']) ?>" required>
<label for="content">Текст</label><textarea id="content" name="content" rows="8" maxlength="10000" required><?= e($note['content']) ?></textarea>
<button type="submit">Сохранить изменения</button><a class="button secondary" href="dashboard.php">Отмена</a>
</form></main></body>
</html>
