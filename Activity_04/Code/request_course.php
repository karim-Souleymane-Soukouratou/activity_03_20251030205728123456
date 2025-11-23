<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// Ensure form was submitted via POST and course_id exists
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
    $email = $_SESSION['email'];
    $courseId = $_POST['course_id'];

    // Check if request already exists
    $stmtCheck = $conn->prepare("SELECT * FROM student_course_requests WHERE student_email = ? AND course_id = ?");
    $stmtCheck->execute([$email, $courseId]);
    if ($stmtCheck->rowCount() > 0) {
        // Already requested
        header("Location: student_dashboard.php?error=already_requested");
        exit();
    }

    // Insert request
    $stmt = $conn->prepare("
        INSERT INTO student_course_requests (student_email, course_id, status)
        VALUES (?, ?, 'pending')
    ");
    $stmt->execute([$email, $courseId]);

    header("Location: student_dashboard.php?success=requested");
    exit();
} else {
    // Invalid access
    header("Location: student_dashboard.php?error=invalid_access");
    exit();
}
?>
