<?php
// Database credentials
$host = "localhost"; // Keep as "localhost" for x10Hosting
$user = "jvzrlwzy_users";
$pass = "XQfHcsWXdaCpfrJSMuFu";
$db = "jvzrlwzy_users";

// Create a connection
$conn = new mysqli($host, $user, $pass, $db);

// Check the connection
if ($conn->connect_error) {
    die("Failed to connect to Database: " . $conn->connect_error);
} else {
    echo "Database connection successful!";
}
?>
