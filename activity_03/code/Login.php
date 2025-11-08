<!-- activity01_login.php -->
<?php
// ---------- PHP Section (runs when form is submitted) ----------

// Fake database of users (you can replace this later with a real MySQL DB)
$users = [
  'student@ashesi.edu.gh' => ['password' => 'student123', 'role' => 'student'],
  'intern@ashesi.edu.gh' => ['password' => 'intern123', 'role' => 'faculty_intern'],
  'lecturer@ashesi.edu.gh' => ['password' => 'lecturer123', 'role' => 'lecturer']
];

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = $_POST['email'] ?? '';
  $password = $_POST['password'] ?? '';
  $role = $_POST['role'] ?? '';

  // Verify user
  if (isset($users[$email]) && $users[$email]['password'] === $password && $users[$email]['role'] === $role) {
    // Redirect to role dashboard
    if ($role === "faculty_intern") {
      header("Location: FI_Dashboard.html");
      exit();
    } elseif ($role === "student") {
      header("Location: student_dashboard.html");
      exit();
    } elseif ($role === "lecturer") {
      header("Location: lecturer_dashboard.html");
      exit();
    }
  } else {
    $error = "Invalid email, password, or role. Please try again.";
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
      color: white;
    }

    .login-box {
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      width: 320px;
      text-align: center;
      color: black;
      margin-top: 15px;
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
    }

    input[type="submit"]:hover {
      background-color: #b32b1a;
    }

    button {
      background-color: #e0e8f1;
      color: white;
      border: none;
      padding: 10px 15px;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
    }

    button:hover {
      background-color: #0056b3;
    }

    .error {
      color: red;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="overlay"></div>

  <div class="login-container">
    <h2>Attendance Management System</h2>
    <div class="login-box"> 
      <img src="ashesi_logo.png" alt="Ashesi Logo" width="120" height="120">

      <!--  Form submits to same PHP file -->
      <form method="POST" action="" onsubmit="return validateForm()">
        <input type="email" id="email" name="email" 
               pattern=".+@ashesi\.edu\.gh" 
               placeholder="Enter your Ashesi email" required
               oninvalid="this.setCustomValidity('Email must end with @ashesi.edu.gh')"
               oninput="this.setCustomValidity('')"><br>

        <input type="password" id="password" name="password" placeholder="Password" required><br>

        <select id="role" name="role" required>
          <option value="">-- Select status --</option>
          <option value="student">Student</option>
          <option value="faculty_intern">Faculty Intern</option>
          <option value="lecturer">Lecturer</option>
        </select><br>

        <input type="submit" value="Login">
        <button type="button">Forgot password</button>

        <!-- Error message (PHP side) -->
        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
      </form>
    </div>
  </div>

  <!-- ---------- JavaScript Section ---------- -->
  <script>
    function validateForm() {
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value.trim();
      const role = document.getElementById("role").value;

      if (!email || !password || !role) {
        alert("Please fill in all fields before logging in.");
        return false; // stop form submission
      }

      // Optional: simple password length check
      if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        return false;
      }

      return true; // allow PHP to process
    }
  </script>
</body>
</html>
