<!DOCTYPE html>
<html>
<head>
    <title>Create a Class</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js"></script>
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

<!-- Notification Container -->
<div id="notification" class="notification"></div>

    <h1>Create a New Class</h1>
    <form method="POST" action="create_class.php">
        <label for="class_name">Class Name:</label>
        <input type="text" id="class_name" name="class_name" required><br>
        <button type="submit">Create Class</button>
    </form>

    <?php
include 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_name = trim($_POST['class_name']); // Trim to avoid extra spaces

    // Check if the class already exists
    $check_stmt = $conn->prepare("SELECT COUNT(*) FROM classes WHERE name = ?");
    $check_stmt->bind_param("s", $class_name);
    $check_stmt->execute();
    $check_stmt->bind_result($class_exists);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($class_exists > 0) {
        echo "<script>showNotification('Class with this name already exists.', 'error');</script>";
    } else {
        // Insert the new class
        $stmt = $conn->prepare("INSERT INTO classes (name, teacher_id) VALUES (?, ?)");
        $stmt->bind_param("si", $class_name, $teacher_id);

        if ($stmt->execute()) {
            echo "<script>showNotification('Class created successfully.', 'success');</script>";
        } else {
            echo "<script>showNotification('Failed to create class.', 'error');</script>";
        }
        $stmt->close();
    }
}
?>
</body>
</html>
