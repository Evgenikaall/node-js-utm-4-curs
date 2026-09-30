const express = require('express');
const path = require('path');
const courseRoutes = require('./routes/courseRoutes');
const logger = require('./middleware/logger');

const app = express();
const PORT = Number(process.env.PORT) || 3000;

app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'views'));

app.use(logger);
app.use(express.urlencoded({ extended: true }));
app.use(express.static(path.join(__dirname, 'public')));

app.get('/', (req, res) => res.redirect('/courses'));
app.use('/courses', courseRoutes);

app.use((req, res) => {
    res.status(404).render('404', {
        title: 'Страница не найдена',
        url: req.originalUrl
    });
});

app.use((error, req, res, next) => {
    console.error(error);
    res.status(500).render('error', { title: 'Внутренняя ошибка сервера' });
});

app.listen(PORT, () => {
    console.log(`Server is running at http://localhost:${PORT}`);
});
