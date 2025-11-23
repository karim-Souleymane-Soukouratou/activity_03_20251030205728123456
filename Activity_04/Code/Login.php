<?php
session_start();
require 'db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Fetch user by email + role
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = ?");
    $stmt->execute([$email, $role]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify password
    if ($user && password_verify($password, $user['password'])) {

        // Store session data
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['name'] = $user['name'];

        // Redirect based on role
        if ($user['role'] === "student") {
            header("Location: Student_Dashboard.php");
        } elseif ($user['role'] === "faculty_intern") {
            header("Location: FI_Dashboard.php");
        } elseif ($user['role'] === "faculty") {
            header("Location: faculty_dashboard.php");
        } else {
            $error = "Unknown role!";
            exit();
        }

        exit();

    } else {
        $error = "Invalid email, password, or role!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Page</title>
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

    .login-container {
        position: relative;
        z-index: 1;
        text-align: center;
    }

    .login-box {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        width: 320px;
        text-align: center;
        color: black;
    }

    .login-box img {
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

<div class="login-container">
    <div class="login-box">
        <img src="ashesi_logo.png" alt="Ashesi Logo" width="120" height="120">
        <h2>Login</h2>

        <?php
        if ($error) echo "<p class='error'>$error</p>";
        ?>

        <form method="POST" action="login.php">
            <input type="email" name="email" placeholder="Enter your Ashesi email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <select name="role" required>
                <option value="">-- Select Role --</option>
                <option value="student">Student</option>
                <option value="faculty_intern">Faculty Intern</option>
                <option value="faculty">Faculty</option>
            </select><br>
            <input type="submit" value="Login">
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</div>
</body>
</html>
