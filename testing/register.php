<?php
include 'connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_POST['signUp'])) {
    $firstName = trim($_POST['fName']);
    $lastName = trim($_POST['lName']);
    $email = trim($_POST['email']);
    $password = md5(trim($_POST['password']));

    
    $profile_image = $_FILES['profile_image'];

    if ($profile_image && $profile_image['error'] == UPLOAD_ERR_OK) {
        $image_name = basename($profile_image['name']);
        $target_dir = "profile_uploads/";

        
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        $target_file = $target_dir . $image_name;

        
        if (move_uploaded_file($profile_image['tmp_name'], $target_file)) {
            echo "Profile image uploaded successfully!";
        } else {
            echo "Failed to upload profile image.";
        }
    } else {
        echo "No profile image selected or an error occurred.";
        $target_file = '';
    }

    
    $checkEmail = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($checkEmail);

    if ($result->num_rows > 0) {
        echo "Email Address Already Exists!";
    } else {
        
        $insertQuery = "INSERT INTO users(firstName, lastName, email, password, profile_image)
                         VALUES ('$firstName', '$lastName', '$email', '$password', '$target_file')";

        if ($conn->query($insertQuery) === TRUE) {
            header("Location: index.php");
            exit();
        } else {
            echo "Database Error: " . $conn->error;
        }
    }
}

if (isset($_POST['signIn'])) {
    $email = trim($_POST['email']);
    $password = md5(trim($_POST['password']));

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        header("Location: homepage.php");
        exit();
    } else {
        echo "Not Found, Incorrect Email or Password";
    }
}
?>