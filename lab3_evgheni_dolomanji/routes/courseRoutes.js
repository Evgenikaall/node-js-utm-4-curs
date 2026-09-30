const express = require('express');
const controller = require('../controllers/courseController');
const validateCourse = require('../middleware/validateCourse');

const router = express.Router();

router.get('/', controller.getCourses);
router.get('/new', controller.showCreateForm);
router.post('/', validateCourse('create'), controller.createCourse);
router.get('/:id/edit', controller.showEditForm);
router.post('/:id/edit', validateCourse('edit'), controller.updateCourse);
router.post('/:id/delete', controller.deleteCourse);
router.get('/:id', controller.showCourse);

module.exports = router;
