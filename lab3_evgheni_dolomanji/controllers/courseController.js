const courses = require('../models/courseModel');

function findCourse(id) {
    return courses.find((course) => course.id === Number(id));
}

function renderNotFound(req, res) {
    return res.status(404).render('404', {
        title: 'Курс не найден',
        url: req.originalUrl
    });
}

function getCourses(req, res) {
    const search = String(req.query.search || '').trim();
    const semester = String(req.query.semester || '').trim();
    let filteredCourses = courses;

    if (search) {
        const normalizedSearch = search.toLowerCase();
        filteredCourses = filteredCourses.filter((course) =>
            course.title.toLowerCase().includes(normalizedSearch) ||
            course.teacher.toLowerCase().includes(normalizedSearch)
        );
    }

    if (semester) {
        filteredCourses = filteredCourses.filter((course) => course.semester === Number(semester));
    }

    res.render('courses/index', {
        title: 'Список курсов',
        courses: filteredCourses,
        filters: { search, semester }
    });
}

function showCourse(req, res) {
    const course = findCourse(req.params.id);
    if (!course) return renderNotFound(req, res);

    res.render('courses/details', { title: course.title, course });
}

function showCreateForm(req, res) {
    res.render('courses/create', {
        title: 'Добавление курса',
        course: {},
        errors: []
    });
}

function createCourse(req, res) {
    const course = {
        id: Math.max(0, ...courses.map((item) => item.id)) + 1,
        ...req.courseData
    };

    courses.push(course);
    res.redirect('/courses');
}

function showEditForm(req, res) {
    const course = findCourse(req.params.id);
    if (!course) return renderNotFound(req, res);

    res.render('courses/edit', {
        title: 'Редактирование курса',
        course,
        errors: []
    });
}

function updateCourse(req, res) {
    const course = findCourse(req.params.id);
    if (!course) return renderNotFound(req, res);

    Object.assign(course, req.courseData);
    res.redirect(`/courses/${course.id}`);
}

function deleteCourse(req, res) {
    const index = courses.findIndex((course) => course.id === Number(req.params.id));
    if (index === -1) return renderNotFound(req, res);

    courses.splice(index, 1);
    res.redirect('/courses');
}

module.exports = {
    getCourses,
    showCourse,
    showCreateForm,
    createCourse,
    showEditForm,
    updateCourse,
    deleteCourse
};
