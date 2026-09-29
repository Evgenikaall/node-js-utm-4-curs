const books = require('../models/bookModel');
const authors = require('../models/authorModel');

function findBook(id) {
    return books.find((book) => book.id === Number(id));
}

function getBooks(req, res) {
    const { genre, year } = req.query;
    let result = books;

    if (genre) {
        result = result.filter((book) => book.genre.toLowerCase() === genre.toLowerCase());
    }

    if (year) {
        result = result.filter((book) => book.year === Number(year));
    }

    res.json(result);
}

function getBookById(req, res) {
    const book = findBook(req.params.id);

    if (!book) {
        return res.status(404).json({ error: 'Book not found' });
    }

    res.json(book);
}

function searchBooks(req, res) {
    const title = String(req.query.title || '').trim().toLowerCase();
    const result = books.filter((book) => book.title.toLowerCase().includes(title));

    res.json(result);
}

function createBook(req, res) {
    const { title, authorId, genre, year } = req.body;

    if (!title || authorId === undefined || !genre || year === undefined) {
        return res.status(400).json({ error: 'title, authorId, genre and year are required' });
    }

    const author = authors.find((item) => item.id === Number(authorId));
    if (!author) {
        return res.status(400).json({ error: 'Author not found' });
    }

    const book = {
        id: Math.max(0, ...books.map((item) => item.id)) + 1,
        title,
        authorId: Number(authorId),
        genre,
        year: Number(year)
    };

    books.push(book);
    res.status(201).json(book);
}

function updateBook(req, res) {
    const book = findBook(req.params.id);

    if (!book) {
        return res.status(404).json({ error: 'Book not found' });
    }

    const { title, authorId, genre, year } = req.body;
    if (authorId !== undefined && !authors.some((author) => author.id === Number(authorId))) {
        return res.status(400).json({ error: 'Author not found' });
    }

    if (title !== undefined) book.title = title;
    if (authorId !== undefined) book.authorId = Number(authorId);
    if (genre !== undefined) book.genre = genre;
    if (year !== undefined) book.year = Number(year);

    res.json(book);
}

function deleteBook(req, res) {
    const index = books.findIndex((book) => book.id === Number(req.params.id));

    if (index === -1) {
        return res.status(404).json({ error: 'Book not found' });
    }

    const [deletedBook] = books.splice(index, 1);
    res.json(deletedBook);
}

module.exports = { getBooks, getBookById, searchBooks, createBook, updateBook, deleteBook };
