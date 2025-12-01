<?php
session_start();

if (!isset($_SESSION['user'])) {
    header('Location: login.html'); // Redirect to login page if not logged in
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - Fusion Fit</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h1>
    <p>You have successfully logged in.</p>
    <a href="logout.php">Logout</a>
</body>
</html>