<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Sign Up Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin-top: 50px;
        }

        form {
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            width: 300px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        input {
            margin-bottom: 10px;
            padding: 8px;
            width: 90%;
        }

        button {
            padding: 10px;
            width: 95%;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <h1>Login and Sign Up</h1>
    <!-- Sign Up Form -->
    <form action="signup.php" method="post" enctype="multipart/form-data">
        <h2>Sign Up</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="file" name="profile_pic" accept="image/*" required>
        <button type="submit">Sign Up</button>
    </form>

    <!-- Login Form -->
    <form action="login.php" method="post">
        <h2>Log In</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Log In</button>
    </form>
</body>

</html>