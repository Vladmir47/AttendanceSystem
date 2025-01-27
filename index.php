<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Student Attendance System</title>
</head>
<body>
<div id="menu-icon" class="menu-container" onclick="toggleSidebar(this)">
    <div class="bar bar1"></div>
    <div class="bar bar2"></div>
    <div class="bar bar3"></div>
</div>
<!-- Navigation Bar -->
    <nav id="sidebar" class="sidebar">
        <a class="active" href="index.php">Home</a>
        <a href="create_class.php">Create Class</a>
        <a href="add_student.php">Add Student</a>
        <a href="view_students.php">View Students</a>
        <a href="mark_attendance.php">Mark Attendance</a>
        <a href="view_attendance.php">View Attendance Reports</a>
    </nav>

<!-- Header Section -->
<header>
    <h1>Student Attendance Management System</h1>
</header>

<!-- Main Content Section -->
<div class="container">
    <h2>Welcome to the Attendance Management System</h2>
    <p>This system helps manage student attendance and generate reports efficiently. Select an option from the sidebar menu to get started.</p>
</div>
<script src="assets/js/script.js"></script>
</body>
</html>
