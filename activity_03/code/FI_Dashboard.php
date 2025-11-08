<?php
// Start session to check if user logged in
session_start();

// Example: restrict access if user not logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

// You can later replace this with a database query
$userName = "Prof. Angela Owusu Ansah";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="FI_dashboard.css" rel="stylesheet">
    <title>Faculty Attendance Dashboard</title>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-content">
            <h1>Ashesi Attendance System</h1>
            <div class="navbar-user">
                <?php echo htmlspecialchars($userName); ?>
                <div class="user-avatar">
                    <?php echo strtoupper(substr($userName, 0, 2)); ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <!-- Tab Navigation -->
        <div class="tabs">
            <button class="tab active" onclick="switchTab('courses')">My Courses</button>
            <button class="tab" onclick="switchTab('sessions')">Class Sessions</button>
            <button class="tab" onclick="switchTab('reports')">Reports</button>
        </div>

        <!-- Courses Tab -->
        <div id="courses" class="tab-content active">
            <div class="course-grid">
                <div class="course-card" onclick="alert('View course details')">
                    <div class="course-code">CS101</div>
                    <div class="course-name">Introduction to Computer Science</div>
                    <div class="course-info">
                        <span>45 Students</span>
                        <span>Mon, Wed, Fri</span>
                    </div>
                </div>

                <div class="course-card" onclick="alert('View course details')">
                    <div class="course-code">CS201</div>
                    <div class="course-name">Data Structures & Algorithms</div>
                    <div class="course-info">
                        <span>38 Students</span>
                        <span>Tue, Thu</span>
                    </div>
                </div>

                <div class="course-card" onclick="alert('View course details')">
                    <div class="course-code">CS301</div>
                    <div class="course-name">Database Management Systems</div>
                    <div class="course-info">
                        <span>42 Students</span>
                        <span>Mon, Wed</span>
                    </div>
                </div>

                <div class="course-card" onclick="alert('View course details')">
                    <div class="course-code">CS401</div>
                    <div class="course-name">Software Engineering</div>
                    <div class="course-info">
                        <span>35 Students</span>
                        <span>Tue, Thu, Fri</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sessions Tab -->
        <div id="sessions" class="tab-content">
            <div class="session-list">
                <div class="session-header">
                    <h2>Recent Class Sessions</h2>
                    <button class="btn btn-primary" onclick="alert('Start new attendance session')">+ New Session</button>
                </div>

                <div class="session-item">
                    <div class="session-details">
                        <h3>CS101 - Introduction to Computer Science</h3>
                        <div class="session-meta">Today, 9:00 AM - 10:30 AM</div>
                    </div>
                    <div class="session-stats">
                        <div class="stat"><div class="stat-value">42</div><div class="stat-label">Present</div></div>
                        <div class="stat"><div class="stat-value">3</div><div class="stat-label">Absent</div></div>
                        <span class="attendance-badge badge-high">93% Attendance</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Tab -->
        <div id="reports" class="tab-content">
            <div class="reports-grid">
                <div class="report-card">
                    <h3>Overall Attendance</h3>
                    <div class="report-value">88.2%</div>
                    <div class="report-change change-positive">↑ 2.3% from last month</div>
                </div>

                <div class="report-card">
                    <h3>Total Students</h3>
                    <div class="report-value">160</div>
                    <div class="report-change change-positive">↑ 5 new enrollments</div>
                </div>

                <div class="report-card">
                    <h3>Classes This Week</h3>
                    <div class="report-value">12</div>
                    <div class="report-change">4 courses active</div>
                </div>

                <div class="report-card">
                    <h3>Avg. Class Size</h3>
                    <div class="report-value">40</div>
                    <div class="report-change">Students per class</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => content.classList.remove('active'));

            const tabs = document.querySelectorAll('.tab');
            tabs.forEach(tab => tab.classList.remove('active'));

            document.getElementById(tabName).classList.add('active');
            event.target.classList.add('active');
        }
    </script>
</body>
</html>
