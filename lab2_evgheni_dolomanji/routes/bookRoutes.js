const express = require('express');
const controller = require('../controllers/bookController');

const router = express.Router();

router.get('/search', controller.searchBooks);
router.get('/', controller.getBooks);
router.get('/:id', controller.getBookById);
router.post('/', controller.createBook);
router.patch('/:id', controller.updateBook);
router.delete('/:id', controller.deleteBook);

module.exports = router;
