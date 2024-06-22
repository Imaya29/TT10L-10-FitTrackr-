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

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $mysqli->real_escape_string($_POST['fullname']);
    $age = (int)$_POST['age'];
    $height = (float)$_POST['height'];
    $weight = (float)$_POST['weight'];

    $sql = "UPDATE users SET fullname='$fullname', age='$age', height='$height', weight='$weight' WHERE username='$username'";
    
    if ($mysqli->query($sql) === TRUE) {
        $success = 'Information updated successfully!';
    } else {
        $error = 'Error updating information: ' . $mysqli->error;
    }
}

$sql = "SELECT * FROM users WHERE username='$username'";
$result = $mysqli->query($sql);
$user = $result->fetch_assoc();

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Information</title>
    <link rel="stylesheet" href="styleupdateprofile.css">
</head>
<body>
    <div class="update-container">
        <h2>Update Information</h2>
        <form method="post" action="update_info.php">
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" value="<?php echo htmlspecialchars($user['age']); ?>" required>

            <label for="height">Height (cm):</label>
            <input type="number" id="height" name="height" value="<?php echo htmlspecialchars($user['height']); ?>" required>

            <label for="weight">Weight (kg):</label>
            <input type="number" id="weight" name="weight" value="<?php echo htmlspecialchars($user['weight']); ?>" required>

            <button type="submit">Update</button>
            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>
            <?php if ($success): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>
