const courses = require('../models/courseModel');

function validateCourse(formType) {
    return (req, res, next) => {
        const { title, teacher, credits, semester, description } = req.body;
        const errors = [];
        const creditsNumber = Number(credits);
        const semesterNumber = Number(semester);

        if (!title || !title.trim()) errors.push('Название дисциплины обязательно');
        if (!teacher || !teacher.trim()) errors.push('Преподаватель обязателен');
        if (!Number.isFinite(creditsNumber) || creditsNumber <= 0) {
            errors.push('Количество кредитов должно быть положительным числом');
        }
        if (!Number.isInteger(semesterNumber) || semesterNumber < 1 || semesterNumber > 8) {
            errors.push('Семестр должен быть целым числом в диапазоне от 1 до 8');
        }
        if (!description || !description.trim()) errors.push('Описание обязательно');

        if (errors.length === 0) {
            req.courseData = {
                title: title.trim(),
                teacher: teacher.trim(),
                credits: creditsNumber,
                semester: semesterNumber,
                description: description.trim()
            };
            return next();
        }

        if (formType === 'edit') {
            const course = courses.find((item) => item.id === Number(req.params.id));
            if (!course) {
                return res.status(404).render('404', {
                    title: 'Курс не найден',
                    url: req.originalUrl
                });
            }

            return res.status(400).render('courses/edit', {
                title: 'Редактирование курса',
                course: { ...course, ...req.body },
                errors
            });
        }

        return res.status(400).render('courses/create', {
            title: 'Добавление курса',
            course: req.body,
            errors
        });
    };
}

module.exports = validateCourse;
