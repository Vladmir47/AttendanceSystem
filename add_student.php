<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js"></script>
    <title>Add Student</title>
</head>
<body>

<nav>
    <a href="index.php">Home</a>
    <a href="add_student.php">Add Student</a>
    <a href="view_students.php">View Students</a>
    <a href="mark_attendance.php">Mark Attendance</a>
    <a href="view_attendance.php">View Attendance Reports</a>
</nav>
<header>
    <h1>Add Student</h1>
</header>

<div class="container">
    <form method="POST" onsubmit="return validateForm(this);">
        <label for="name">Student Name</label>
        <input type="text" name="name" id="name" required>
        
        <label for="course">Course</label>
        <input type="text" name="course" id="course" required>
        
        <button type="submit" name="submit">Add Student</button>
    </form>
    <?php
    if (isset($_POST['submit'])) {
        $name = $_POST['name'];
        $course = $_POST['course'];

        $sql = "INSERT INTO students (name, course) VALUES ('$name', '$course')";
        if ($conn->query($sql) === TRUE) {
            echo "<p>Student added successfully!</p>";
        } else {
            echo "<p>Error: " . $conn->error . "</p>";
        }
    }
    ?>
</div>
</body>
</html>
