<?php
session_start();
require 'db.php';

if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'faculty') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
    $requestId = $_POST['request_id'];
    $action = $_POST['action']; // approve or reject

    // Update the request status
    $stmt = $conn->prepare("UPDATE student_course_requests SET status = ? WHERE id = ?");
    $stmt->execute([$action, $requestId]);

    // If approved, add to student_enrollments
    if ($action === 'approve') {
        // Fetch course_id and student_email
        $stmt2 = $conn->prepare("SELECT student_email, course_id FROM student_course_requests WHERE id = ?");
        $stmt2->execute([$requestId]);
        $req = $stmt2->fetch(PDO::FETCH_ASSOC);

        $stmt3 = $conn->prepare("INSERT INTO student_enrollments (student_email, course_id) VALUES (?, ?)");
        $stmt3->execute([$req['student_email'], $req['course_id']]);
    }

    header("Location: faculty_dashboard.php");
    exit();
}
?>
