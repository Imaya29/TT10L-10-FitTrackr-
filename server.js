const express = require('express');
const fs = require('fs');
const path = require('path');
const logger = require('./logger'); // Import the logger
const app = express();
const port = 3000;

// Middleware to parse JSON and URL-encoded bodies
app.use(express.urlencoded({ extended: true }));
app.use(express.json());

// Serve static files from the 'public' directory
app.use(express.static(path.join(__dirname, 'public')));

// Path to store user data
const dataFilePath = path.join(__dirname, 'data.txt');

// Create data file if it doesn't exist
if (!fs.existsSync(dataFilePath)) {
    try {
        fs.writeFileSync(dataFilePath, '[]', 'utf8');
        logger.info('Created new data file at: ' + dataFilePath);
    } catch (err) {
        logger.error('Error creating data file: ' + err);
    }
} else {
    logger.info('Data file exists at: ' + dataFilePath);
}

// POST endpoint to handle form submissions (signup and login)
app.post('/submit_form', (req, res) => {
    const { action, name, email, password } = req.body;

    fs.readFile(dataFilePath, 'utf8', (err, fileData) => {
        if (err) {
            logger.error(`Error reading file: ${dataFilePath}`, err);
            return res.status(500).send('Internal Server Error: Unable to read data file.');
        }

        logger.debug('Read data from file: ' + fileData); // Debug log

        let jsonData = [];
        if (fileData.length > 0) {
            try {
                jsonData = JSON.parse(fileData);
            } catch (parseError) {
                logger.error('Error parsing JSON data: ' + parseError);
                return res.status(500).send('Internal Server Error: Invalid JSON data.');
            }
        }

        if (action === 'signup') {
            const existingUser = jsonData.find(user => user.email === email);
            if (existingUser) {
                return res.status(400).send('User already exists.');
            }

            jsonData.push({ name, email, password });
            fs.writeFile(dataFilePath, JSON.stringify(jsonData), 'utf8', (err) => {
                if (err) {
                    logger.error('Error writing file: ' + err);
                    return res.status(500).send('Internal Server Error: Unable to write data file.');
                }

                res.status(200).send({ message: 'Sign-up successful!' });
                logger.info(`New user signed up: ${email}`);
            });
        } else if (action === 'login') {
            const user = jsonData.find(user => user.email === email && user.password === password);
            if (user) {
                res.status(200).send({ message: 'Log in successful!', user });
                logger.info(`User logged in: ${email}`);
            } else {
                res.status(400).send('Invalid email or password.');
                logger.warn(`Failed login attempt: ${email}`);
            }
        } else {
            res.status(400).send('Invalid action.');
            logger.warn('Invalid action received.');
        }
    });
});

// Default route to serve index.html
app.get('/', (req, res) => {
    res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

// Start server
app.listen(port, () => {
    logger.info(`Server is running on http://localhost:${port}`);
});
