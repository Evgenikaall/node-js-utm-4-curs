# Лабораторная работа №1 — простой HTTP-сервер на Node.js

## Цель работы

Познакомиться с Node.js и npm, создать HTTP-сервер с помощью встроенного модуля `http`, обрабатывать URL и HTTP-методы, возвращать текстовые и JSON-ответы.

## Требования

- Node.js 18 или новее;
- npm (устанавливается вместе с Node.js).

Проверить установку можно командами:

```bash
node -v
npm -v
```

## Запуск

```bash
cd /Users/evgheniidolomanji/IdeaProjects/node-univer/lab1_evgheni_dolomanji
npm start
```

После запуска сервер доступен по адресу <http://localhost:3000>. Для остановки нажмите `Ctrl + C` в терминале.

## Структура проекта

```text
lab1_evgheni_dolomanji/
├── app.js        # HTTP-сервер и обработчики маршрутов
├── package.json  # настройки проекта и команда npm start
└── README.md     # описание работы
```

## Реализованные маршруты

| Метод | URL | Ответ |
| --- | --- | --- |
| GET | `/` | `Home page` |
| GET | `/about` | `About page` |
| GET | `/api/student` | Данные студента в JSON |
| GET | `/time` | Текущие дата и время |
| GET | `/api/courses` | Массив дисциплин в JSON |
| GET | `/api/university` | Информация об университетском API в JSON |
| Любой | Неизвестный URL | `404 - Page not found` и статус `404` |

Примеры проверки в браузере или через `curl`:

```bash
curl http://localhost:3000/
curl http://localhost:3000/api/student
curl http://localhost:3000/api/courses
curl -i http://localhost:3000/not-found
```

## Как устроен сервер

`http.createServer()` создаёт HTTP-сервер. При каждом запросе callback получает:

- `req` — объект запроса; из него используются `req.method` и `req.url`;
- `res` — объект ответа; через него устанавливаются заголовки, статус и тело ответа.

Обработчики хранятся в объекте `routes`. Его ключ состоит из метода и пути, например `GET /about`. Поэтому для добавления нового маршрута достаточно добавить ещё один обработчик, без длинной цепочки `if / else`.

Для JSON-ответов сервер устанавливает заголовок `Content-Type: application/json; charset=utf-8` и преобразует JavaScript-объект с помощью `JSON.stringify()`.

## Логирование и ошибки

Каждый запрос выводится в терминал в формате:

```text
GET /about | 29.09.2026 18:30
```

Если обработчик для пути не найден, сервер отправляет HTTP-статус `404` с помощью `res.writeHead(404, ...)` и текст `404 - Page not found`.

## Перед сдачей

Замените `YOUR_GROUP` в [app.js](app.js) на номер своей учебной группы.
