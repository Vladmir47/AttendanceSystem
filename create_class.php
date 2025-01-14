<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_name = $_POST['class_name'];
    // $teacher_id = $_SESSION['teacher_id'];

    $stmt = $conn->prepare("INSERT INTO classes (name) VALUES (?)");
    $stmt->bind_param("s", $class_name);

    if ($stmt->execute()) {
        echo "Class created successfully.";
    } else {
        echo "Failed to create class.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create a Class</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<nav>
    <a href="index.php">Home</a>
    <a href="create_class.php">Create class</a>
    <a href="add_student.php">Add Student</a>
    <a href="view_students.php">View Students</a>
    <a href="mark_attendance.php">Mark Attendance</a>
    <a href="view_attendance.php">View Attendance Reports</a>
</nav>
    <h1>Create a New Class</h1>
    <form method="POST" action="create_class.php">
        <label for="class_name">Class Name:</label>
        <input type="text" id="class_name" name="class_name" required><br>
        <button type="submit">Create Class</button>
    </form>
</body>
</html>
