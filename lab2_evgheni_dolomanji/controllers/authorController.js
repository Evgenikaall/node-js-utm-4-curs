const authors = require('../models/authorModel');
const books = require('../models/bookModel');

function getAuthors(req, res) {
    res.json(authors);
}

function getAuthorById(req, res) {
    const author = authors.find((item) => item.id === Number(req.params.id));

    if (!author) {
        return res.status(404).json({ error: 'Author not found' });
    }

    res.json(author);
}

function getAuthorBooks(req, res) {
    const authorId = Number(req.params.id);
    const author = authors.find((item) => item.id === authorId);

    if (!author) {
        return res.status(404).json({ error: 'Author not found' });
    }

    res.json(books.filter((book) => book.authorId === authorId));
}

module.exports = { getAuthors, getAuthorById, getAuthorBooks };
