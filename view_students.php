<?php include 'db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>View Students</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="assets/js/script.js"></script>
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
    <h1>View students</h1>
</header>
    <div class="container">
        <h1>All Students</h1>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Course</th>
                <th>Action</th>
                
            </tr>

            <?php
            $result = $conn->query("SELECT * FROM students");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['course']}</td>
                        <td>
                            <a href='edit_student.php?id={$row['id']}'>Edit</a>
                            <a href='delete_student.php?id={$row['id']}' onclick='return confirmDelete();'>Delete</a>
                        </td>
                    </tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>
