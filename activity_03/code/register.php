<?php
session_start();
require 'db.php';

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $error = "Email already registered!";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $passwordHash, $role])) {
            $success = "Registration successful! You can now login.";
        } else {
            $error = "Registration failed. Try again!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register Page</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-image: url('Admi.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100vh;
        color: white;
    }

    .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.4);
        z-index: 0;
    }

    .register-container {
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .register-box {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        width: 320px;
        text-align: center;
        color: black;
    }

    .register-box img {
        margin-bottom: 20px;
    }

    input, select {
        padding: 10px;
        margin: 8px 0;
        width: 90%;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    input[type="submit"] {
        background-color: #921e12;
        color: white;
        border: none;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
        padding: 10px;
        border-radius: 5px;
    }

    input[type="submit"]:hover {
        background-color: #b32b1a;
    }

    .error {
        color: red;
        margin: 5px 0;
    }

    .success {
        color: green;
        margin: 5px 0;
    }

    a {
        color: #921e12;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
</style>
</head>
<body>
<div class="overlay"></div>

<div class="register-container">
    <div class="register-box">
        <img src="ashesi_logo.png" alt="Ashesi Logo" width="120" height="120">
        <h2>Register</h2>

        <?php
        if ($error) echo "<p class='error'>$error</p>";
        if ($success) echo "<p class='success'>$success</p>";
        ?>

        <form method="POST" action="register.php">
            <input type="text" name="name" placeholder="Full Name" required><br>
            <input type="email" name="email" placeholder="Ashesi Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <select name="role" required>
                <option value="">-- Select Role --</option>
                <option value="student">Student</option>
                <option value="faculty_intern">Faculty Intern</option>
                <option value="lecturer">Lecturer</option>
            </select><br>
            <input type="submit" value="Register">
        </form>
        <p>Already registered? <a href="login.php">Login here</a></p>
    </div>
</div>
</body>
</html>
