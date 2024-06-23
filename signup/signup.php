<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $mysqli->real_escape_string($_POST['username']);
    $password = password_hash($mysqli->real_escape_string($_POST['password']), PASSWORD_BCRYPT);
    $email = $mysqli->real_escape_string($_POST['email']);
    $fullname = $mysqli->real_escape_string($_POST['fullname']);
    $age = (int)$_POST['age'];
    $height = (float)$_POST['height'];
    $weight = (float)$_POST['weight'];
    $ideal_weight = isset($_POST['ideal_weight']) ? (float)$_POST['ideal_weight'] : null;

    $sql = "INSERT INTO users (username, password, email, fullname, age, height, weight, ideal_weight) 
            VALUES ('$username', '$password', '$email', '$fullname', $age, $height, $weight, $ideal_weight)";

    if ($mysqli->query($sql) === TRUE) {
        header('Location: /TT10L-10-FitTrackr-/login/login.html');
        exit();
    } else {
        $error = 'Error: ' . $mysqli->error;
        echo $error; // Output the MySQL error for debugging
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8">
		<title>RegistrationForm_v6 by Colorlib</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- MATERIAL DESIGN ICONIC FONT -->
		<link rel="stylesheet" href="fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">
		
		<!-- STYLE CSS -->
		<link rel="stylesheet" href="css/style.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form</title>
    <!-- Link to your CSS file -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="wrapper">
    <div class="inner">
        <div class="image-holder">
            <img src="images/registration-form-6.jpg" alt="Registration Form Image">
        </div>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <h3>Register Here</h3>
            <div class="form-row">
                <input type="text" class="form-control" placeholder="Username" name="username" required>
                <input type="password" class="form-control" placeholder="Password" name="password" required>
                <input type="email" class="form-control" placeholder="Email" name="email" required>
            </div>
            <div class="form-row">
                <input type="text" class="form-control" placeholder="Full Name" name="fullname" required>
                <input type="number" class="form-control" placeholder="Age" name="age" required>
                <input type="number" class="form-control" placeholder="Height" name="height" required>
                <input type="number" class="form-control" placeholder="Weight" name="weight" required>
            </div>
            <div class="form-row">
                <input type="number" class="form-control" placeholder="Ideal Weight" name="ideal_weight">
            </div>
            <button type="submit">Register Now <i class="zmdi zmdi-long-arrow-right"></i></button>
        </form>
    </div>
</div>

<!-- Link to your jQuery and custom JavaScript file -->
<script src="js/jquery-3.3.1.min.js"></script>
<script src="js/main.js"></script>

</body>
</html>
