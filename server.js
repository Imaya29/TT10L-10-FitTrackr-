const express = require('express');
const fs = require('fs');
const path = require('path');
const bcrypt = require('bcrypt'); // Import bcrypt for password hashing
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

// Function to write JSON data to file
function writeUserData(data, callback) {
    fs.writeFile(dataFilePath, JSON.stringify(data), 'utf8', (err) => {
        if (err) {
            logger.error('Error writing file: ' + err);
            return callback(err);
        }
        logger.info('User data saved successfully.');
        callback(null);
    });
}

// POST endpoint to handle form submissions (signup and login)
app.post('/submit_form', (req, res) => {
    const { action, name, email, password } = req.body;

    fs.readFile(dataFilePath, 'utf8', (err, fileData) => {
        if (err) {
            logger.error(`Error reading file: ${dataFilePath}`, err);
            return res.status(500).send('Internal Server Error: Unable to read data file.');
        }

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
            // Check if the email is already registered
            const existingUser = jsonData.find(user => user.email === email);
            if (existingUser) {
                return res.status(400).send('User already exists.');
            }

            // Hash the password before storing it
            bcrypt.hash(password, 10, (hashErr, hashedPassword) => {
                if (hashErr) {
                    logger.error('Error hashing password: ' + hashErr);
                    return res.status(500).send('Internal Server Error: Unable to hash password.');
                }

                // Store the hashed password along with other user data
                jsonData.push({ name, email, password: hashedPassword });

                // Write updated JSON array back to file
                writeUserData(jsonData, (writeErr) => {
                    if (writeErr) {
                        return res.status(500).send('Internal Server Error: Unable to write data file.');
                    }
                    res.status(200).send({ message: 'Sign-up successful!' });
                });
            });

        } else if (action === 'login') {
            // Find user by email
            const user = jsonData.find(user => user.email === email);

            // Check if user exists and compare hashed password
            if (user) {
                bcrypt.compare(password, user.password, (compareErr, result) => {
                    if (compareErr || !result) {
                        return res.status(400).send('Invalid email or password.');
                    }
                    res.status(200).send({ message: 'Log in successful!', user: { name: user.name, email: user.email } });
                });
            } else {
                res.status(400).send('Invalid email or password.');
            }
        } else {
            res.status(400).send('Invalid action.');
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
