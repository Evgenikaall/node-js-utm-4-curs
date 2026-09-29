const http = require('http');

const PORT = 3000;

function formatDateTime(date = new Date()) {
    const pad = (value) => String(value).padStart(2, '0');

    return [
        pad(date.getDate()),
        pad(date.getMonth() + 1),
        date.getFullYear()
    ].join('.') + ` ${pad(date.getHours())}:${pad(date.getMinutes())}`;
}

const routes = {
    'GET /': (req, res) => {
        res.setHeader('Content-Type', 'text/plain; charset=utf-8');
        res.end('Home page');
    },
    'GET /about': (req, res) => {
        res.setHeader('Content-Type', 'text/plain; charset=utf-8');
        res.end('About page');
    },
    'GET /api/student': (req, res) => {
        const student = {
            name: 'Evgheni Dolomanji',
            group: 'IAFR2302',
            speciality: 'Informatica applicata'
        };

        res.setHeader('Content-Type', 'application/json; charset=utf-8');
        res.end(JSON.stringify(student));
    },
    'GET /time': (req, res) => {
        res.setHeader('Content-Type', 'text/plain; charset=utf-8');
        res.end(formatDateTime());
    },
    'GET /api/courses': (req, res) => {
        const courses = [
            {
                title: 'Web Technologies',
                teacher: 'Irina Popescu',
                credits: 5
            },
            {
                title: 'Database Systems',
                teacher: 'Mihai Rusu',
                credits: 6
            },
            {
                title: 'Applied Informatics',
                teacher: 'Elena Ceban',
                credits: 4
            }
        ];

        res.setHeader('Content-Type', 'application/json; charset=utf-8');
        res.end(JSON.stringify(courses));
    },
    'GET /api/university': (req, res) => {
        const university = {
            name: 'University Information Service',
            message: 'Welcome to the student API'
        };

        res.setHeader('Content-Type', 'application/json; charset=utf-8');
        res.end(JSON.stringify(university));
    }
};

const server = http.createServer((req, res) => {
    console.log(`${req.method} ${req.url} | ${formatDateTime()}`);

    const handler = routes[`${req.method} ${req.url}`];

    if (handler) {
        handler(req, res);
        return;
    }

    res.writeHead(404, { 'Content-Type': 'text/plain; charset=utf-8' });
    res.end('404 - Page not found');
});

server.listen(PORT, () => {
    console.log(`Server is running at http://localhost:${PORT}`);
});
