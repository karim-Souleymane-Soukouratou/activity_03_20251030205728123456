<?php
session_start();
require 'db.php';

// Check the login
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$email = $_SESSION['email'];

// Fetch student infomation
$stmt = $conn->prepare("SELECT name, role FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    session_destroy();
    header("Location: login.php");
    exit();
}

$userName = $user['name'];
$userRole = $user['role'];

// this is to make it so that only students can have access to the studant page
if ($userRole !== 'student') {
    echo "<p>Access denied. Only students can view this page.</p>";
    exit();
}

// --- fetch all availabe courses so they appear in the stodent dashboard to  ---
$courses = $conn->query("SELECT * FROM courses")->fetchAll(PDO::FETCH_ASSOC);

// --- fetch  courses  a student is enrolled in ---
$enrolledStmt = $conn->prepare("
    SELECT c.* FROM student_enrollments e
    JOIN courses c ON e.course_id = c.id
    WHERE e.student_email = ?
");
$enrolledStmt->execute([$email]);
$enrolledCourses = $enrolledStmt->fetchAll(PDO::FETCH_ASSOC);

// --- fetch pending request ---
$requestStmt = $conn->prepare("
    SELECT course_id FROM student_course_requests 
    WHERE student_email = ? AND status = 'pending'
");
$requestStmt->execute([$email]);
$pendingRequests = $requestStmt->fetchAll(PDO::FETCH_COLUMN);

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="FI_dashboard.css" rel="stylesheet">
    <title>Student Dashboard</title>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar-content">
        <h1>Student Course Dashboard</h1>
        <div class="navbar-user">
            <?php echo htmlspecialchars($userName); ?>
            <div class="user-avatar"><?php echo strtoupper(substr($userName, 0, 2)); ?></div>
        </div>
    </div>
</nav>

<div class="container">

    <!-- TABS -->
    <div class="tabs">
        <button class="tab active" onclick="switchTab('available')">Available Courses</button>
        <button class="tab" onclick="switchTab('enrolled')">My Enrolled Courses</button>
    </div>

    <!-- AVAILABLE COURSES -->
    <div id="available" class="tab-content active">
        <h2>Available Courses</h2>
        <div class="course-grid">

            <?php foreach ($courses as $course): ?>
                <div class="course-card">
                    <div class="course-code"><?php echo $course['course_code']; ?></div>
                    <div class="course-name"><?php echo $course['course_name']; ?></div>

                    <?php if (in_array($course['id'], $pendingRequests)): ?>
                        <button class="btn btn-disabled">Request Pending</button>

                    <?php elseif (in_array($course['id'], array_column($enrolledCourses, 'id'))): ?>
                        <button class="btn btn-disabled">Already Enrolled</button>

                    <?php else: ?>
                        <form method="POST" action="request_course.php">
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            <button class="btn btn-primary">Request to Join</button>
                        </form>
                    <?php endif; ?>

                </div>
            <?php endforeach; ?>

        </div>
    </div>

    <!-- ENROLLED COURSES -->
    <div id="enrolled" class="tab-content">
        <h2>My Enrolled Courses</h2>
        <div class="course-grid">

            <?php if (empty($enrolledCourses)): ?>
                <p>You are not enrolled in any course yet.</p>
            <?php endif; ?>

            <?php foreach ($enrolledCourses as $course): ?>
                <div class="course-card">
                    <div class="course-code"><?php echo $course['course_code']; ?></div>
                    <div class="course-name"><?php echo $course['course_name']; ?></div>
                    <span class="badge">Enrolled</span>
                </div>
            <?php endforeach; ?>

        </div>
    </div>

</div>

<script>
function switchTab(tabName) {
    const tabs = document.querySelectorAll('.tab-content');
    tabs.forEach(t => t.classList.remove('active'));

    const buttons = document.querySelectorAll('.tab');
    buttons.forEach(b => b.classList.remove('active'));

    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}
</script>

</body>
</html>
