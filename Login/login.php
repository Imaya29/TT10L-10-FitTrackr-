<?php
session_start();

$host = 'localhost';
$db = 'fittrackr';
$user = 'root'; // Replace with your MySQL username
$pass = ''; // Replace with your MySQL password

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$error = '';
$success = '';

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success = 'Registration successful! Please log in.';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = $mysqli->real_escape_string($_POST['password']);

    $result = $mysqli->query("SELECT * FROM users WHERE username='$username'");
    
    if ($result) {
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['username'] = $username;
                header('Location: \TT10L-10-FitTrackr-/workout/index.html'); // Redirect after successful login
                exit();
            } else {
                $error = 'Invalid password. Please try again.';
            }
        } else {
            $error = 'No user found with that username. Please try again.';
        }
    } else {
        $error = 'Error: ' . $mysqli->error;
    }
}

$mysqli->close();
?>
