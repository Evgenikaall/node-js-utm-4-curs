const express = require('express');
const controller = require('../controllers/authorController');

const router = express.Router();

router.get('/', controller.getAuthors);
router.get('/:id/books', controller.getAuthorBooks);
router.get('/:id', controller.getAuthorById);

module.exports = router;
