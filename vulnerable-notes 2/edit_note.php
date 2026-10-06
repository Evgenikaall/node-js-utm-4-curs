<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = $_GET['id'] ?? '0';

$note = $pdo->query("SELECT * FROM notes WHERE id = $id")->fetch(PDO::FETCH_ASSOC);

if (!$note) {
    die('Заметка не найдена. SQL: SELECT * FROM notes WHERE id = ' . $id);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $content = $_POST['content'] ?? '';

    $pdo->exec(
        "UPDATE notes SET title = '$title', content = '$content' WHERE id = $id"
    );

    header('Location: dashboard.php');
    exit;
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Изменение заметки</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="card">
    <h1>Изменение заметки #<?= $id ?></h1>
    <form method="post">
        <label>Заголовок</label>
        <input type="text" name="title" value="<?= $note['title'] ?>">

        <label>Текст</label>
        <textarea name="content" rows="8"><?= $note['content'] ?></textarea>

        <button type="submit">Сохранить изменения</button>
        <a class="button secondary" href="dashboard.php">Отмена</a>
    </form>
</main>
</body>
</html>

