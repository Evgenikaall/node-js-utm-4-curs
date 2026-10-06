CREATE DATABASE IF NOT EXISTS vulnerable_notes
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE vulnerable_notes;

DROP TABLE IF EXISTS notes;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE notes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_notes_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

INSERT INTO users (name, email, password, role) VALUES
('Администратор', 'admin@example.local', 'admin123', 'admin'),
('Анна', 'anna@example.local', 'student123', 'user'),
('Иван', 'ivan@example.local', 'student123', 'user');

INSERT INTO notes (user_id, title, content) VALUES
(1, 'Системная заметка', 'Проверить права доступа к административной странице.'),
(2, 'Личная заметка Анны', 'Эту запись не должен изменять другой пользователь.'),
(3, 'Личная заметка Ивана', 'Проверить защиту от IDOR и XSS.');

