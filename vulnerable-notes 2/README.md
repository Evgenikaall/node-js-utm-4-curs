# Vulnerable Notes

Учебное PHP-приложение для лабораторной работы по безопасности веб-приложений.


## Требования

- PHP 8.0 или новее
- MySQL или MariaDB
- Apache
- Laragon или XAMPP
- phpMyAdmin


1. Выполните импорт. Скрипт автоматически создаст базу данных `vulnerable_notes`, таблицы и демонстрационные записи.

2. Проверьте параметры подключения в `config.php`:

   ```php
   $host = 'localhost';
   $database = 'vulnerable_notes';
   $username = 'root';
   $password = '';
   ```

3. Откройте приложение

## Демонстрационные аккаунты

| Роль | Email | Пароль |
|---|---|---|
| Администратор | `admin@example.local` | `admin123` |
| Пользователь | `anna@example.local` | `student123` |
| Пользователь | `ivan@example.local` | `student123` |

Пароли намеренно сохранены в базе данных в открытом виде. Это одна из проблем, которую необходимо исправить.

## Структура проекта

```text
vulnerable-notes/
├── index.php
├── register.php
├── login.php
├── dashboard.php
├── add_note.php
├── edit_note.php
├── delete_note.php
├── admin.php
├── logout.php
├── config.php
├── database.sql
├── LABORATORNAYA_RABOTA.md
└── assets/
    └── style.css