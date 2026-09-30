function formatDateTime(date = new Date()) {
    const pad = (value) => String(value).padStart(2, '0');
    const datePart = [pad(date.getDate()), pad(date.getMonth() + 1), date.getFullYear()].join('.');
    const timePart = `${pad(date.getHours())}:${pad(date.getMinutes())}`;

    return `${datePart} ${timePart}`;
}

function logger(req, res, next) {
    console.log(`${req.method} | ${req.originalUrl} | ${formatDateTime()}`);
    next();
}

module.exports = logger;
