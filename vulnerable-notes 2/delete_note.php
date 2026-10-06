<?php
require_once 'config.php';

$userId = require_login();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    fail_request(405, 'Метод не поддерживается.');
}
verify_csrf();
$id = positive_id($_POST['id'] ?? null);
try {
    $statement = $pdo->prepare('DELETE FROM notes WHERE id = :id AND user_id = :user_id');
    $statement->execute([':id' => $id, ':user_id' => $userId]);
    if ($statement->rowCount() !== 1) {
        secure_log('note_delete_denied', ['user_id' => $userId, 'note_id' => $id]);
        fail_request(404, 'Запрошенная заметка не найдена.');
    }
    secure_log('note_deleted', ['user_id' => $userId, 'note_id' => $id]);
    redirect('dashboard.php');
} catch (PDOException $exception) {
    secure_log('note_delete_failed', ['user_id' => $userId, 'note_id' => $id, 'code' => $exception->getCode()]);
    fail_request(500, 'Не удалось удалить заметку. Попробуйте позже.');
}
