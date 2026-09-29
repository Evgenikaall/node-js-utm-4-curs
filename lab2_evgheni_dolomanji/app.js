const express = require('express');
const books = require('./models/bookModel');
const authors = require('./models/authorModel');
const logger = require('./middleware/logger');
const bookRoutes = require('./routes/bookRoutes');
const authorRoutes = require('./routes/authorRoutes');

const app = express();
const PORT = Number(process.env.PORT) || 3000;

app.use(express.json());
app.use(logger);

app.use('/api/books', bookRoutes);
app.use('/api/authors', authorRoutes);

app.get('/api/statistics', (req, res) => {
    const years = books.map((book) => book.year);
    const newestBook = books.find((book) => book.year === Math.max(...years));
    const oldestBook = books.find((book) => book.year === Math.min(...years));

    res.json({
        booksCount: books.length,
        authorsCount: authors.length,
        genresCount: new Set(books.map((book) => book.genre)).size,
        newestBook,
        oldestBook
    });
});

app.use((req, res) => {
    res.status(404).json({ error: 'Route not found' });
});

app.listen(PORT, () => {
    console.log(`Server is running at http://localhost:${PORT}`);
});
