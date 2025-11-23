<?php
session_start();
require 'db.php';

// Only allow faculty
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'faculty') {
    header("Location: login.php?error=access_denied");
    exit();
}

$facultyEmail = $_SESSION['email'];
$facultyName = $_SESSION['name'];
$error = "";
$success = "";

// Handle creating a new course
if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['course_code'], $_POST['course_name'])) {
    $courseCode = $_POST['course_code'];
    $courseName = $_POST['course_name'];

    $stmt = $conn->prepare("INSERT INTO courses (course_code, course_name, created_by) VALUES (?, ?, ?)");
    if ($stmt->execute([$courseCode, $courseName, $facultyEmail])) {
        $success = "Course created successfully!";
    } else {
        $error = "Failed to create course.";
    }
}

// Fetch courses created by this faculty
$courses = $conn->prepare("SELECT * FROM courses WHERE created_by = ?");
$courses->execute([$facultyEmail]);
$courses = $courses->fetchAll(PDO::FETCH_ASSOC);

// Fetch pending student requests
$requests = $conn->prepare("
    SELECT r.id, r.student_email, r.course_id, c.course_code, c.course_name
    FROM student_course_requests r
    JOIN courses c ON r.course_id = c.id
    WHERE c.created_by = ? AND r.status = 'pending'
");
$requests->execute([$facultyEmail]);
$requests = $requests->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link href="FI_dashboard.css" rel="stylesheet">
<title>Faculty Dashboard</title>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
    <div class="navbar-content">
        <h1>Faculty Dashboard</h1>
        <div class="navbar-user">
            <?php echo htmlspecialchars($facultyName); ?>
            <div class="user-avatar"><?php echo strtoupper(substr($facultyName, 0, 2)); ?></div>
        </div>
    </div>
</nav>

<div class="container">

    <!-- TABS -->
    <div class="tabs">
        <button class="tab active" onclick="switchTab('courses')">My Courses</button>
        <button class="tab" onclick="switchTab('requests')">Student Requests</button>
    </div>

    <!-- COURSES TAB -->
    <div id="courses" class="tab-content active">
        <?php if($error) echo "<p class='error'>$error</p>"; ?>
        <?php if($success) echo "<p class='success'>$success</p>"; ?>

        <h3>Create New Course</h3>
        <form method="POST" action="">
            <input type="text" name="course_code" placeholder="Course Code" required>
            <input type="text" name="course_name" placeholder="Course Name" required>
            <input type="submit" value="Create Course">
        </form>

        <h3>My Courses</h3>
        <div class="course-grid">
            <?php foreach($courses as $course): ?>
            <div class="course-card">
                <div class="course-code"><?php echo $course['course_code']; ?></div>
                <div class="course-name"><?php echo $course['course_name']; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- STUDENT REQUESTS TAB -->
    <div id="requests" class="tab-content">
        <h3>Pending Student Requests</h3>
        <?php if(empty($requests)) echo "<p>No pending requests</p>"; ?>
        <?php foreach($requests as $req): ?>
        <div class="request-card">
            <strong><?php echo $req['student_email']; ?></strong> wants to join
            <strong><?php echo $req['course_code']; ?> - <?php echo $req['course_name']; ?></strong>
            <form method="POST" action="handle_request.php" style="display:inline;">
                <input type="hidden" name="request_id" value="<?php echo $req['id']; ?>">
                <button type="submit" name="action" value="approve" class="btn btn-approve">Approve</button>
                <button type="submit" name="action" value="reject" class="btn btn-reject">Reject</button>
            </form>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<script>
function switchTab(tabName) {
    const tabContents = document.querySelectorAll('.tab-content');
    tabContents.forEach(tc => tc.classList.remove('active'));

    const tabs = document.querySelectorAll('.tab');
    tabs.forEach(tb => tb.classList.remove('active'));

    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}
</script>

</body>
</html>
