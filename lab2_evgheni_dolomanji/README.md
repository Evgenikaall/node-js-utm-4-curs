# Лабораторная работа №2 — REST API на Express.js

## Цель работы

Создать REST API с использованием Express.js, освоить маршруты, параметры URL, query-параметры, JSON-запросы и организацию приложения по MVC.

## Запуск

```bash
cd /Users/evgheniidolomanji/IdeaProjects/node-univer/lab2_evgheni_dolomanji
npm install
npm start
```

Сервер запускается по адресу <http://localhost:3000>. Остановить его можно сочетанием `Ctrl + C`.

> Если порт 3000 занят, можно выбрать другой: `PORT=3001 npm start`.

## Структура MVC

```text
lab2_evgheni_dolomanji/
├── app.js
├── controllers/
│   ├── authorController.js  # логика работы с авторами
│   └── bookController.js    # логика работы с книгами
├── middleware/
│   └── logger.js            # журналирование всех запросов
├── models/
│   ├── authorModel.js       # массив авторов
│   └── bookModel.js         # массив книг
├── routes/
│   ├── authorRoutes.js      # маршруты авторов
│   └── bookRoutes.js        # маршруты книг
├── package.json
└── README.md
```

- **Model** хранит данные. В этой работе это массивы в памяти: 5 книг и 3 автора.
- **Controller** получает запрос, проверяет данные и формирует ответ.
- **Router** связывает URL и HTTP-метод с нужным контроллером.
- **app.js** подключает middleware и роутеры, а также запускает сервер.

Данные не сохраняются в базе: после перезапуска сервера добавленные или изменённые книги возвращаются к исходному состоянию.

## Маршруты API

| Метод | URL | Назначение |
| --- | --- | --- |
| GET | `/api/books` | Все книги |
| GET | `/api/books?genre=fantasy&year=1937` | Фильтрация по жанру и/или году |
| GET | `/api/books/search?title=node` | Поиск по части названия без учёта регистра |
| GET | `/api/books/:id` | Книга по идентификатору |
| POST | `/api/books` | Добавление книги |
| PATCH | `/api/books/:id` | Частичное изменение книги |
| DELETE | `/api/books/:id` | Удаление книги |
| GET | `/api/authors` | Все авторы |
| GET | `/api/authors/:id` | Автор по идентификатору |
| GET | `/api/authors/:id/books` | Книги выбранного автора |
| GET | `/api/statistics` | Статистика библиотеки |

`GET /api/statistics` возвращает количество книг, авторов и жанров, а также самую новую и самую старую книгу.

Неизвестный путь возвращает:

```json
{ "error": "Route not found" }
```

со статусом `404`.

## Примеры запросов

Получить все книги:

```bash
curl http://localhost:3000/api/books
```

Получить фантастические книги 1937 года:

```bash
curl 'http://localhost:3000/api/books?genre=fantasy&year=1937'
```

Добавить книгу:

```bash
curl -X POST http://localhost:3000/api/books \
  -H 'Content-Type: application/json' \
  -d '{"title":"Clean Code","authorId":3,"genre":"programming","year":2008}'
```

При успешном добавлении сервер вернёт созданную книгу со статусом `201 Created`. Поля `title`, `authorId`, `genre` и `year` обязательны. Автор с указанным `authorId` должен существовать, иначе возвращается `400 Bad Request`.

Изменить год книги с ID 1:

```bash
curl -X PATCH http://localhost:3000/api/books/1 \
  -H 'Content-Type: application/json' \
  -d '{"year":1938}'
```

Удалить книгу с ID 1:

```bash
curl -X DELETE http://localhost:3000/api/books/1
```

## `req.params`, `req.query` и `req.body`

- `req.params` — параметр, входящий в путь. В `GET /api/books/2` значение `req.params.id` равно `"2"`.
- `req.query` — параметры после знака `?`. В `/api/books?genre=fantasy` значение `req.query.genre` равно `"fantasy"`.
- `req.body` — тело запроса. Для POST и PATCH с JSON оно становится доступным после `app.use(express.json())`.

## Middleware логирования

`middleware/logger.js` подключён через `app.use(logger)`, поэтому запускается до каждого маршрута и выводит строку вида:

```text
GET | /api/books | 29.09.2026 18:30
```

## Коды ответов

| Код | Когда возвращается |
| --- | --- |
| `200 OK` | Успешное чтение, изменение или удаление данных |
| `201 Created` | Новая книга успешно создана |
| `400 Bad Request` | Не переданы обязательные поля или указан несуществующий автор |
| `404 Not Found` | Книга, автор или маршрут не найдены |

## Node.js и Express.js

Node.js — среда выполнения JavaScript вне браузера. Она содержит стандартный модуль `http`, с которым сервер и маршруты нужно обрабатывать вручную.

Express.js — framework, работающий поверх Node.js. Он предоставляет удобные методы `app.get()`, `app.post()`, `express.Router()`, middleware и автоматическую отправку JSON через `res.json()`. Поэтому Express позволяет строить REST API быстрее и с более понятной структурой.
